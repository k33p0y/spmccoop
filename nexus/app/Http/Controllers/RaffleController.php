<?php

namespace App\Http\Controllers;

use App\Models\Raffle;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RaffleController extends Controller
{
    public function prepareRequest(Request $request)
    {
        if ($request->has('name')) { $request->merge(['name' => strip_tags($request->get('name'))]); }
    }

    public function index() {
        $winners = DB::select(
            "SELECT
                r.id,
                u.name winner,
                e.name election,
                r.price,
                r.created_at
            FROM spmccoop.raffles r
            LEFT JOIN spmccoop.users u ON u.id = r.winner_user_id
            LEFT JOIN spmccoop.elections e ON e.id = r.election_id
            ORDER BY created_at DESC"
        );
        return $winners;
    }

    public function store(Request $request) {
        $this->prepareRequest($request);

        $winner = new Raffle();
        $winner->winner_user_id = $request->winner_user_id;
        $winner->election_id = $request->election_id;
        $winner->price = $request->price;
        $winner->save();

        return response([
            'message' => 'Winner saved to database.'
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request) {
        $this->prepareRequest($request);

        $raffle = Raffle::findOrFail($request->id);
        $raffle->price = $request->price;
        $raffle->save();

        return response([
            'message' => 'Price updated successfully.'
        ], Response::HTTP_OK);
    }


    public function printWinners(Request $request) {
        $raffle_ids = $request->get('ids');
        $winners = DB::table('raffles as r')
            ->leftJoin('users as u', 'u.id', '=', 'r.winner_user_id')
            ->leftJoin('elections as e', 'e.id', '=', 'r.election_id')
            ->select('u.id as user_id', 'u.name', 'r.price', 'e.name as election')
            ->whereIn('r.id', $raffle_ids)
            ->get();
        $dompdf = new Dompdf();
		$options = $dompdf->getOptions();
		$options->setIsPhpEnabled(true);
		$dompdf->setOptions($options);

        // $dompdf->loadHtml('hello world');
        $dompdf->loadHtml($this->createHTMLTemplate($winners));

		// $dompdf->setPaper('folio', 'portrait');
        $customPaper = array(0,0,612,396);
        // (Optional) Setup the paper size and orientation
		// $dompdf->setPaper('folio', 'landscape');
		$dompdf->setPaper($customPaper);
		// Render the HTML as PDF
		$dompdf->render();

		// Render the HTML as PDF
		$dompdf->render();

        $date = str_replace(".", "", date('d-m-y'));

        // Output the generated PDF to Browser
        $dompdf->stream("raffle-winners-".$date.".pdf");

    }

    public function createHTMLTemplate($winners) {

        $rows = '';
        for ($i=0; $i<count($winners); $i++){
            $rows = $rows.'
            <tr style="border-bottom: 1px solid black;">
                <td class="table-body-row" style="width: 40%">'.$winners[$i]->name.'</td>
                <td class="table-body-row" style="width: 60%">'.$winners[$i]->price.'</td>
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
                                <th style="font-size: 15px" colspan="2">SOUTHERN PHILIPPINES MEDICAL CENTER</th>
                            </tr>
                            <tr>
                                <th style="font-size: 15px" colspan="2">EMPLOYEES CREDIT COOPERATIVE</th>
                            </tr>
                            <tr style="margin-top: 5px; margin-bottom: 5px;">
                                <th style="font-size: 14px"  colspan="2">Raffle Winners</th>
                            </tr><br><br>
                        </thead>
                        <tbody style="margin-top: 5px;">
                            <tr>
                                <td class="table-body-head center-text" style="width: 40%">Name</td>
                                <td class="table-body-head center-text" style="width: 60%">Price</td>
                            </tr>'.$rows.'
                        </tbody>
                    </table>
                    <br><br><br><br>
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 30%;"></td>
                                <td class="center-text" style="font-size: 13px; border-bottom: 1px solid black; width: 40%;" ></td>
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
