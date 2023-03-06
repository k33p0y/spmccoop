<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ElectionDetailController;
use App\Http\Controllers\RaffleController;


/*
|--------------------------------------------------------------------------
| API Routesd
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'oauth'], function() {
    Route::post('/signin', [AuthController::class, 'authenticate'])->middleware(['throttle:15,1']);
    Route::post('/signout', [AuthController::class, 'invalidate'])->middleware(['auth:api']);
});

Route::middleware('auth:api')->get('user', function (Request $request) {
    // return $request->user();
    return [
        'id' => $request->user()->id,
        'username' => $request->user()->username,
    ];
});

Route::group(['prefix' => 'profile'], function() {
    Route::get('/', [AuthController::class, 'profileBase64'])->middleware(['auth:api']);
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware(['auth:api']);
    Route::post('/update-picture', [AuthController::class, 'updatePicture'])->middleware(['auth:api']);
    Route::get('/profile-picture', [AuthController::class, 'profileBase64'])->middleware(['auth:api']);
    Route::get('/candidate-picture/{id}', [AuthController::class, 'candidateProfileBase64']) ;


});

Route::post('/check-user', [UserController::class, 'checkUser']);
Route::get('/member', [UserController::class, 'index'])->middleware(['auth:api']);
Route::post('/member/store', [UserController::class, 'store'])->middleware(['auth:api']);
Route::patch('/member/update', [UserController::class, 'update'])->middleware(['auth:api']);

Route::group(['prefix' => 'election'], function() {
    Route::get('/', [ElectionController::class, 'index'])->middleware(['auth:api']);
    Route::post('/store', [ElectionController::class, 'store'])->middleware(['auth:api']);
    Route::patch('/update', [ElectionController::class, 'update'])->middleware(['auth:api']);
    // Route::delete('/delete', [ElectionController::class, 'destroy'])->middleware(['auth:api']);

    Route::group(['prefix' => 'vote'], function() {
        Route::post('/', [VoteController::class, 'vote']);
        Route::get('/list', [VoteController::class, 'list']);
        Route::get('/result-blind', [VoteController::class, 'resultBlind']);
        Route::get('/result', [VoteController::class, 'result'])->middleware(['auth:api']);
        Route::get('/voter-list', [VoteController::class, 'voterList'])->middleware(['auth:api']);
        Route::get('/{voter_id}/print/votes', [VoteController::class, 'printIndividualVotes']);
    });
    Route::group(['prefix' => 'position'], function() {
        Route::get('/', [PositionController::class, 'index'])->middleware(['auth:api']);
        Route::post('/store', [PositionController::class, 'store'])->middleware(['auth:api']);
        Route::patch('/update', [PositionController::class, 'update'])->middleware(['auth:api']);
        Route::delete('/delete', [PositionController::class, 'destroy'])->middleware(['auth:api']);
    });
});

Route::group(['prefix' => 'election-detail'], function() {
    Route::get('/', [ElectionDetailController::class, 'index'])->middleware(['auth:api']);
    Route::post('/store', [ElectionDetailController::class, 'store'])->middleware(['auth:api']);
    Route::patch('/update', [ElectionDetailController::class, 'update'])->middleware(['auth:api']);
    Route::delete('/delete', [ElectionDetailController::class, 'destroy'])->middleware(['auth:api']);
    // Route::get('/print/election/result', [ElectionDetailController::class, 'printElectionResult']);
});

Route::group(['prefix' => 'raffle'], function() {
    Route::get('/', [RaffleController::class, 'index'])->middleware(['auth:api']);
    Route::post('/store', [RaffleController::class, 'store'])->middleware(['auth:api']);
    Route::patch('/update', [RaffleController::class, 'update'])->middleware(['auth:api']);
    Route::patch('/invalidate/winner', [RaffleController::class, 'invalidateWinner'])->middleware(['auth:api']);
    Route::get('/current/winners', [RaffleController::class, 'getCurrentWinners'])->middleware(['auth:api']);
    Route::get('/print/winners', [RaffleController::class, 'printWinners']);
});
