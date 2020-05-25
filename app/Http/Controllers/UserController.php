<?php
namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    // Get User By It's ID
    public function getUserById($id) {
        if (!is_numeric($id)) { return response()->json('Invalid ID', 422); }
        $userData = User::find($id);
        if (empty($userData)) { return response()->json('User Does Not Exist!', 404); }
        return view('user', ['user' => $userData]);
    }

    // Update Existing User Comment
    public function update(Request $request) {
        if (Helper::isJson($request->getContent())) {
            $requestData = json_decode($request->getContent(), 1);
            print_r($requestData);
        } else {
            return response()->json('Invalid POST JSON', 422);
        }
        foreach(['password', 'id', 'comments'] as $key){
            if($this->missing_post($requestData,$key) or !$key)
                return response()->json('Missing Value for "'.$key.'"', 422);
        }
        if(strtoupper($requestData['password']) != '720DF6C2482218518FA20FDC52D4DED7ECC043AB') { return response()->json('Invalid password', 401); }
        if (!is_numeric($requestData['id'])) { return response()->json('Invalid ID', 422); }
        $id = $requestData['id'];
        $comments = $requestData['comments'];
        $password = strtoupper($requestData['password']);
        return $this->updateData($id, $comments, $password);
    }

    // Get Old Comment and add new one
    public function updateData($id, $comments, $password = '720DF6C2482218518FA20FDC52D4DED7ECC043AB') {
        $userData = User::find($id);
        if (empty($userData)) { return response()->json('User Does Not Exist!', 404); }
        $userData->update(['comments' => $userData->comments . '<br>' . $comments]);
        return response()->json('OK', 200);
    }

    function missing_post($requestedData,$field){
        return (!isset($requestedData[$field]) or !$requestedData[$field]);
    }

}
?>