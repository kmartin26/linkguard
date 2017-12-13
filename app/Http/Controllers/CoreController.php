<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CoreController extends Controller
{
    /**
     * Create protected urls
     */
    public function create(Request $request)
    {
        /*
         * Validating urls input
         * Return error: NO_URLS_PARAM_PROVIDED
         */
        $validator = Validator::make($request->all(), [
            'urls' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->get('format') === 'txt') {
                return '403 - NO_URLS_PARAM_PROVIDED';
            } else {
                return response()->json([
                    'data' => NULL,
                    'status' => [
                        'code' => 403,
                        'txt' => 'NO_URLS_PARAM_PROVIDED',
                        'message' => 'No urls parameter provided'
                    ]
                ]);
            }

        }

        /*
         * Store request urls in $urls
         */
        $urls_request = $request->get('urls');

        /*
         * Make an array by exploding $urls
         * Filter empty strings
         */
        $urls_array = array_filter(explode(',', $urls_request));

        /*
         * Store urls in clean string
         */
        $urls = implode(',', $urls_array);

        /*
         * Array length
         */
        $urls_count = count($urls_array);

        /*
         * Validate each entry is URL
         * Return error message : NOT_AN_URL
         */
        $validator = Validator::make($urls_array, [
            '*' => 'url',
        ]);

        if ($validator->fails()) {
            if ($request->get('format') === 'txt') {
                return '403 - NOT_AN_URL';
            } else {
                return response()->json([
                    'data' => NULL,
                    'status' => [
                        'code' => 403,
                        'txt' => 'NOT_AN_URL',
                        'message' => 'At least one URL is invalid'
                    ]
                ]);
            }
        }

        /*
         * Generate a delete code
         */
        $delete_code = $this->generateRandomString();

        /*
         * Validation successful
         * Insert record in DB
         */
        $dbinsert = Link::create([
            'urls' => $urls,
            'urls_nb' => $urls_count,
            'client_ip' => $request->ip(),
            'delete_code' => $delete_code,
        ]);

        $link = url('/') . '/' . $dbinsert['id'];

        /*
         * Insert in DB successful
         * Return 200 response with url generated
         */
        if ($request->get('format') === 'txt') {
            return $link;
        } else {
            return response()->json([
                'data' => [
                    'id' => $dbinsert['id'],
                    'urls' => $urls_array,
                    'urls_nb' => $urls_count,
                    'link' => $link,
                    'client_ip' => $request->ip(),
                    'delete_code' => $delete_code,
                ],
                'status' => [
                    'code' => 200,
                    'txt' => 'OK',
                    'message' => 'Successful operation'
                ]
            ]);
        }
    }

    /**
     * Delete protected urls
     */
    public function delete(Request $request)
    {

        $id = $request->get('id');
        $delete_code = $request->get('delete_code');

        /*
         * Check if $id is empty
         * Return error message : NO_url_ID
         */
        if (empty($id)) {
            if ($request->get('format') === 'txt') {
                return '403 - NO_URL_ID';
            } else {
                return response()->json([
                    'data' => NULL,
                    'status' => [
                        'code' => 403,
                        'txt' => 'NO_URL_ID',
                        'message' => 'No url id provided'
                    ]
                ]);
            }
        }

        /*
         * Check if $delete_code is empty
         * Return error message : NO_DELETE_CODE
         */
        if (empty($delete_code)) {
            if ($request->get('format') === 'txt') {
                return '403 - NO_DELETE_CODE';
            } else {
                return response()->json([
                    'data' => NULL,
                    'status' => [
                        'code' => 403,
                        'txt' => 'NO_DELETE_CODE',
                        'message' => 'No delete code provided'
                    ]
                ]);
            }
        }

        /*
         * Get url info for $id
         */
        $db = Link::where('id', $id)->get();

        /*
         * Check if link is already deleted
         * Return error message : LINK_ALREADY_DELETED
         */
        if ($db->first()->deleted === 1) {
            if ($request->get('format') === 'txt') {
                return '403 - LINK_ALREADY_DELETED';
            } else {
                return response()->json([
                    'data' => NULL,
                    'status' => [
                        'code' => 403,
                        'txt' => 'LINK_ALREADY_DELETED',
                        'message' => 'Link is already deleted'
                    ]
                ]);
            }
        }

        /*
         * Compare $delete_code with DB data
         * Return error message : WRONG_DELETE_CODE
         */
        if ($db->first()->delete_code != $delete_code) {
            if ($request->get('format') === 'txt') {
                return '403 - WRONG_DELETE_CODE';
            } else {
                return response()->json([
                    'data' => NULL,
                    'status' => [
                        'code' => 403,
                        'txt' => 'WRONG_DELETE_CODE',
                        'message' => 'Wrong delete code provided'
                    ]
                ]);
            }
        }

        /*
         * Update link info
         * deleted from 0 to 1
         * deleted_reason from NULL to user
         */
        Link::where('id', $id)->update(['deleted' => 1, 'deleted_reason' => 'user']);

        if ($request->get('format') === 'txt') {
            return 'OK';
        } else {
            return response()->json([
                'data' => NULL,
                'status' => [
                    'code' => 200,
                    'txt' => 'OK',
                    'message' => 'Successful operation'
                ]
            ]);
        }
    }

    /*
     * Generate a random string
     */
    public function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
