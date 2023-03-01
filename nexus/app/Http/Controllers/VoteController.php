<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Vote;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\ElectionDetail;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class VoteController extends Controller
{
    public function vote(Request $request) {
        $election = Election::where('status', 1)->first();
        $user = User::where('employee_id', $request->id)->first();

        foreach($request->vote as $key => $val) {
            $position = Position::where('name', $key)->first();

            if(is_array($val)) {
                foreach($val as $elVal) {
                    $electionDetail = ElectionDetail::where('election_id', $election->id)->where('position_id', $position->id)->where('candidate_user_id', $elVal)->first();
                    $vote = new Vote();
                    $vote->election_detail_id = $electionDetail->id;
                    $vote->voter_user_id = $user->id;
                    $vote->save();
                }
            } else {
                $electionDetail = ElectionDetail::where('election_id', $election->id)->where('position_id', $position->id)->where('candidate_user_id', $val)->first();
                $vote = new Vote();
                $vote->election_detail_id = $electionDetail->id;
                $vote->voter_user_id = $user->id;
                $vote->save();
            }
        }

        return response([
            'message' => 'Vote saved successfully.'
        ], Response::HTTP_CREATED);
    }

    public function result(Request $request) {
        $election = Election::where('status', 1)->first();
        $electionDetails = ElectionDetail::where('election_id', $election->id)->get();

        $countVote = [];
        foreach($electionDetails as $electionDetail) {
            $count = Vote::where('election_detail_id', $electionDetail->id)->where('is_verified', '1')->count();
            $candidate = User::where("id", $electionDetail->candidate_user_id)->first();
            $position = Position::where("id", $electionDetail->position_id)->first();
            array_push($countVote, [
                'count' => $count,
                'candidate' => $candidate->name,
                'position' => $position->name,
                'candidate_id' => $candidate->id
            ]);
        }

        $positions = [];
        foreach($countVote as $vote) {
            array_push($positions, $vote['position']);
        }

        array_unique($positions);

        $result = [];
        foreach($positions as $position) {
            $result[$position] = [];
            foreach($countVote as $vote) {
                if($vote['position'] == $position) {
                    array_push($result[$position], [
                        'count' => $vote['count'],
                        'candidate' => $vote['candidate'],
                        'candidate_id' => $vote['candidate_id']
                    ]);
                }
            }
        }

        return $result;

        // $positions = [];
        // foreach($electionDetails as $electionDetail) {
        //     array_push($positions, $electionDetail->position_id);
        // }

        // $positions = array_unique($positions);


        // $count = [];
        // foreach($positions as $position) {
        //     $candidates = ElectionDetail::where('election_id', $request->election_id)->where('position_id', $position)->get();
        //     foreach($candidates as $candidate) {
        //         $count[$position][$candidate] = ElectionDetail::where('election_id', $request->election_id)->where('position_id', $position)->where('candidate_user_id', $candidate)->get();
        //     }
        // }

        // return $count;


    }

    public function list(Request $request){
        $election = Election::where('status', 1)->first();
        $electionDetails = ElectionDetail::where('election_id', $election->id)->get();

        $positions = [];
        foreach($electionDetails as $electionDetail) {
            array_push($positions, $electionDetail->position_id);
        }

        $positions = array_unique($positions);


        $count = [];
        foreach($positions as $position) {
            $candidates = ElectionDetail::where('election_id', $election->id)->where('position_id', $position)->get();
            $position = Position::findOrFail($position);
            foreach($candidates as $candidate) {
                $count[$position->name]['candidates'][$candidate->candidate_user_id] = User::findOrFail($candidate->candidate_user_id)->name;;
            }
            $count[$position->name]['count'] = $position->count;
        }

        return [$election, $count];
    }

    public function voterList() {
        // $election = Election::where('status', 1)->first();
        // $electionDetails = ElectionDetail::where('election_id', $election->id)->get();

        // $users = User::all();

        // $voterList = [];
        // foreach($users as $user) {
        //     $electionDetails = ElectionDetail::where('election_id', $election->id)->where('election_id', $election->id)->get();



        //     $voterListEx = [];
        //     $voterListEx['name'] = $user->name;
        //     // $voterListEx['voted'] =
        // }



        $list = DB::select(
            "SELECT
                u.id id,
                u.name name,
                CASE
                    WHEN COUNT(ev.election_detail_id) > 0 THEN 1
                    ELSE 0
                END
                voted

            FROM public.users u
            LEFT JOIN
                (
                    SELECT * FROM public.votes v
                    LEFT JOIN public.election_details ed ON v.election_detail_id = ed.id
                    LEFT JOIN public.elections e ON ed.election_id = e.id
                    WHERE e.status = 1 and v.is_verified = 1
                )


             ev ON u.id = ev.voter_user_id

            GROUP BY u.id, u.name"
        );

        return $list;





    }

    public function printIndividualVotes(Request $request) {
        $voter_id = $request->voter_id;

        $votes = DB::select(
            "SELECT
                u.name voter,
                e.name election,
                c.name candidate,
                p.name position

            FROM public.votes v
            LEFT JOIN public.users as u ON u.id = v.voter_user_id
            LEFT JOIN public.election_details as ed ON ed.id = v.election_detail_id
            LEFT JOIN public.positions as p ON p.id = ed.position_id
            LEFT JOIN public.elections as e ON e.id = ed.election_id
            LEFT JOIN public.users as c ON c.id = ed.candidate_user_id

            WHERE
                v.voter_user_id = :voter_id AND e.status = 1",
                ['voter_id' => $voter_id]
        );

        $dompdf = new Dompdf();
		$options = $dompdf->getOptions();
		$options->setIsPhpEnabled(true);
		$dompdf->setOptions($options);

        if ($votes) {
            $dompdf->loadHtml($this->createHTMLTemplate($votes));

            // 1 inch = 72 point
            // 1 inch = 2.54 cm
            // 10 cm = 10/2.54*72 = 283.464566929
            // 20 cm = 10/2.54*72 = 566.929133858
            // 8.5 x 5.5 half crosswise (612 x 396)
            $customPaper = array(0,0,612,396);
            // (Optional) Setup the paper size and orientation
            // $dompdf->setPaper('folio', 'landscape');
            $dompdf->setPaper($customPaper);
            // Render the HTML as PDF
            $dompdf->render();

            $date = str_replace(".", "", date('d-m-y'));
            if (count($votes)) {
                $dompdf->stream($votes[0]->voter."_".$date.".pdf");
            } else {
                $dompdf->stream($date.".pdf");
            }
            DB::table('votes as v')
                ->leftJoin('election_details as ed', 'ed.id', '=', 'v.election_detail_id')
                ->leftJoin('elections as e', 'e.id', '=', 'ed.election_id')
                ->where('v.voter_user_id', $voter_id)
                ->where('e.status', '=', 1)
                ->update(['v.is_verified'=> '1']); // update to verified if vote is printed
        } else {
            $dompdf->loadHtml('Member did not cast vote yet.');
            $customPaper = array(0,0,612,396);
            $dompdf->setPaper($customPaper);
            $dompdf->render();
            $date = str_replace(".", "", date('d-m-y'));
            $dompdf->stream($date.".pdf");
        }
    }

    public function createHTMLTemplate($votes) {
        $rows = '';
        for ($i=0; $i<count($votes); $i++){
            $rows = $rows.'
            <tr style="border-bottom: 1px solid black;">
                <td class="table-body-row" style="width: 40%">'.$votes[$i]->position.'</td>
                <td class="table-body-row" style="width: 60%">'.$votes[$i]->candidate.'</td>
            </tr>
            ';
        }
        return '
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Document</title>
                    <style>
                        @page { margin: 10px; }
                        body { margin: 10px; font-family: Helvetica; }
                        th { text-align: center; }
                        table { width: 100%; border-collapse: collapse;}
                        .center-text { text-align: center; }
                        .right-text { text-align: right; }
                        .bold-text { font-weight: bold }
                        .table-body-head { font-size: 13px; padding: 4px; font-weight: bold; border: 1px solid black;}
                        .table-body-row { font-size: 13px; padding: 4px; border: 1px solid black;}

                    </style>
                </head>

                <body>
                    <table>
                        <thead>
                            <tr>
                                <th style="font-size: 15px" colspan="2">SOUTHERN PHILIPPINES MEDICAL CENTER COOPERATIVE</th>
                            </tr>
                            <tr>
                                <th style="font-size: 15px" colspan="2">EMPLOYEES CREDIT COOPERATIVE</th>
                            </tr>
                            <tr style="margin-top: 5px; margin-bottom: 5px;">
                                <th style="font-size: 14px"  colspan="2"><u>'.$votes[0]->election.'</u></th>
                            </tr>
                            <tr style="margin-top: 5px; margin-bottom: 5px;">
                                <th style="font-size: 14px"  colspan="2">Summary of Votes</th>
                            </tr><br><br>
                        </thead>
                        <tbody style="margin-top: 5px;">
                            <tr>
                                <td class="table-body-head center-text" style="width: 40%">Position</td>
                                <td class="table-body-head center-text" style="width: 60%">Candidate</td>
                            </tr>'.$rows.'
                        </tbody>
                    </table>
                    <br><br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 30%;"></td>
                                <td class="center-text" style="font-size: 13px; border-bottom: 1px solid black; width: 40%;" >'.$votes[0]->voter.'</td>
                                <td style="width: 30%;"></td>
                            </tr>
                            <tr>
                                <td style="width: 30%;"></td>
                                <td class="center-text" style="font-size: 10px; width: 40%;" >Name and Signature</td>
                                <td style="width: 30%;"></td>
                            </tr>
                        </tbody>
                    </table>


                    <script type="text/php">
                        if ( isset($pdf) ) {
                            // OLD
                            // $font = Font_Metrics::get_font("helvetica", "bold");
                            // $pdf->page_text(72, 18, "{PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(255,0,0));
                            // v.0.7.0 and greater
                            $x = 306;
                            $y = 380;
                            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
                            $font = $fontMetrics->get_font("helvetica", "bold");
                            $size = 5;
                            $color = array(0,0,0);
                            $word_space = 0.0;  //  default
                            $char_space = 0.0;  //  default
                            $angle = 0.0;   //  default
                            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                        }
                    </script>
                </body>
            </html>
		';
    }
}
