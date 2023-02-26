<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\ElectionDetail;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ElectionDetailController extends Controller
{
    public function index(Request $request) {
        $election = Election::where('status', 1)->first();
        $electionDetails = ElectionDetail::where('election_id', $election->id)->orderBy('position_id', 'ASC')->get();


        $electionDetailsEv = [];
        foreach($electionDetails as $electionDetail) {
            $position = Position::where('id', $electionDetail->position_id)->first();
            $candidate = User::where('id', $electionDetail->candidate_user_id)->first();
            $electionDetailsEx = [];
            $electionDetailsEx['position'] = $position->name;
            $electionDetailsEx['candidate'] = $candidate->name;
            $electionDetailsEx['election'] = $election->name;
            $electionDetailsEx['id'] = $electionDetail->id;
            $electionDetailsEx['election_id'] = $electionDetail->election_id;
            $electionDetailsEx['position_id'] = $electionDetail->position_id;
            $electionDetailsEx['candidate_user_id'] = $electionDetail->candidate_user_id;

            array_push($electionDetailsEv, $electionDetailsEx);
        }

        $candidates = User::where('status', 1)->where('candidate', 1)->get();
        $candidatesList = [];
        foreach($candidates as $candidate) {
            $candidateEv = [];
            $candidateEv['label'] = $candidate->name;
            $candidateEv['value'] = $candidate->id;
            array_push($candidatesList, $candidateEv);
        }

        $positions = Position::all();
        $positionList = [];
        foreach($positions as $position) {
            $positionEv = [];
            $positionEv['label'] = $position->name;
            $positionEv['value'] = $position->id;
            array_push($positionList, $positionEv);
        }

        return [$electionDetailsEv, $candidatesList, $positionList];
    }

    public function store(Request $request) {
        $election = Election::where('status', 1)->first();
        $electionDetail = new ElectionDetail();
        $electionDetail->election_id = $election->id;
        $electionDetail->position_id = $request->position_id;
        $electionDetail->candidate_user_id = $request->candidate_user_id;
        $electionDetail->created_by = Auth::user()->id;
        $electionDetail->created_at = date('Y-m-d H:i:s');
        $electionDetail->save();

        return response([
            'message' => 'An election detail has been created successfully.'
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request) {
        $electionDetail = ElectionDetail::findOrFail($request->id);
        $electionDetail->position_id = $request->position_id;
        $electionDetail->candidate_user_id = $request->candidate_user_id;
        $electionDetail->updated_by = $request->user()->id;
        $electionDetail->updated_at = date('Y-m-d H:i:s');
        $electionDetail->save();

        return response([
            'message' => 'An election detail has been updated successfully.'
        ], Response::HTTP_CREATED);
    }

    public function destroy(Request $request) {
        $election = ElectionDetail::findOrFail($request->id);
        $election->delete();

        return response([
            'message' => 'An election detail has been deleted successfully.'
        ], Response::HTTP_OK);
    }
}
