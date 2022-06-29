<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Http\Requests;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Redirect to Homepage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Protect new links
     * @param Request $request
     * @return string
     */
    public function create(Request $request)
    {
        /*
         * Validate form values
         */
        $validator = Validator::make($request->all(), [
            'urls' => 'required',
        ]);

        if ($validator->fails()) {
            // If errors we redirect the user to the home route with the error message
            return redirect()->route('front.index')->withInput()->withErrors('You must enter at least one URL.');
        }

        /*
         * Parse textarea to make an array of urls
         * Filter empty strings
         */
        $urls_array = array_filter(explode("\r\n", $request->get('urls')));

        /*
         * Make separated string of urls
         */
        $urls_separated = implode('|', $urls_array);

        /*
         * Curl request API
         */
        $response = Curl::to(route('api.create'))
            ->withData([
                'urls' => $urls_separated,
                'client_ip' => $request->ip()
            ])
            ->post();

        /*
         * Convert JSON response to Array
         */
        $responseArray = json_decode($response, true);

        /*
         * Check API response
         */
        if (!empty($responseArray)) {
            if ($responseArray['status']['code'] === 403) {
                if ($responseArray['status']['txt'] === 'NO_LINKS_PARAM_PROVIDED') {
                    return redirect()->route('front.index')->withInput()->withErrors($responseArray['status']['message']);
                } elseif ($responseArray['status']['txt'] === 'NOT_AN_URL') {
                    return redirect()->route('front.index')->withInput()->withErrors($responseArray['status']['message']);
                }
            }
    
            if ($responseArray['status']['code'] === 200) {
                return view('create')->with('data', $responseArray);
            }
        }

        return redirect()->route('front.index')->withInput()->withErrors('Something wrong happened, please try again later!');
    }

    /**
     * Display reCaptcha and check for ID exist
     * @param $id
     * @param Request $request
     * @return string
     */
    public function getShow($id, Request $request)
    {
        /*
         * Query link from DB
         */
        $db = Link::where('id', $id)->get();

        /*
         * Check if link exist
         * Return 404 page
         */
        if (count($db) === 0) {
            return abort(404);
        }

        /*
         * Return {id}/show view
         */
        return view('show')->with('id', $id);
    }

    /**
     * Check for reCaptcha validation and display links
     * @param $id
     * @param Request $request
     * @return string
     */
    public function postShow($id, Request $request) {

        /*
         * Check if reCaptcha has been validated before
         */
        if (!$request->session()->has('g-recaptcha-passed')) {
            /*
             * Validate reCaptcha if not in session
             */
            $validator = Validator::make($request->all(), [
                'g-recaptcha-response' => 'required|captcha',
            ]);

            /*
             * If validation fails return error to user
             */
            if ($validator->fails()) {
                // If errors we redirect the user to the home route with the error message
                return redirect()->route('front.show.get', ['id' => $id])->withInput()->withErrors($validator);
            }
        }

        /*
         * Query link from DB
         */
        $db = Link::where('id', $id)->get();

        $urls_array = unserialize($db->first()->urls);

        /*
         * Check if link is deleted
         */
        if ($db->first()->deleted === 1) {
            $data = [
                'data' => [
                    'deleted' => $db->first()->deleted,
                    'deleted_reason' => $db->first()->deleted_reason,
                ],
                'status' => [
                    'code' => 403,
                ]
            ];
        } else {
            $urls = $urls_array;
            $data = [
                'data' => [
                    'id' => $id,
                    'urls' => $urls,
                    'urls_nb' => $db->first()->urls_nb,
                ],
                'status' => [
                    'code' => 200,
                ]
            ];
        }

        /*
         * Put reCaptcha validation into session
         */
        $request->session()->put('g-recaptcha-passed', true);

        /*
         * Return show view with data to user
         */
        return view('show')->with('data', $data);
    }

    /**
     * Delete a protected link
     * @param $id
     * @return string
     */
    public function getDelete($id)
    {
        /*
         * Get link info for $id
         */
        $db = Link::where('id', $id)->get();

        /*
         * Check for empty DB data
         */
        if (count($db) === 0) {
            $data = [
                'data' => [
                    'id' => $id,
                    'deleted' => NULL,
                    'delete_reason' => NULL,
                ],
                'status' => [
                    'code' => 404,
                ]
            ];
        } else {
            $data = [
                'data' => [
                    'id' => $id,
                    'deleted' => $db->first()->deleted,
                    'delete_reason' => $db->first()->deleted_reason,
                ],
                'status' => [
                    'code' => 200,
                ]
            ];
        }

        /*
         * Return delete view with data to user
         */
        return view('delete')->with('data', $data);
    }

    /*
     * Delete a protected link
     */
    public function postDelete($id, Request $request)
    {
        /*
         * Check if delete_code is provided by user
         */
        $validator = Validator::make($request->all(), [
            'delete_code' => 'required',
        ]);

        /*
         * If validation fails return error to user
         */
        if ($validator->fails()) {
            // If errors we redirect the user to the home route with the error message
            return redirect()->route('front.delete.get')->withInput()->withErrors('No delete code entered!');
        }

        /*
         * Set $delete_code from request
         */
        $delete_code = $request->get('delete_code');

        /*
         * Curl request API
         */
        $response = Curl::to(url('api/delete'))
            ->withData(['id' => $id, 'delete_code' => $delete_code])
            ->delete();

        /*
         * Convert JSON response to Array
         */
        $responseArray = json_decode($response, true);

        /*
         * Check for API errors
         */
        if ($responseArray['status']['code'] === 403) {
            if ($responseArray['status']['txt'] === 'LINK_ALREADY_DELETED') {
                return redirect()->back()->withInput()->withErrors($responseArray['status']['message']);
            } elseif ($responseArray['status']['txt'] === 'WRONG_DELETE_CODE') {
                return redirect()->back()->withInput()->withErrors($responseArray['status']['message']);
            }
        }

        /*
         * If API success return info to user
         */
        if ($responseArray['status']['code'] === 200) {
            //return redirect()->action('HomeController@getDelete', ['id' => $id]);
            return redirect()->back()->with('success', 'Your link has been deleted!');
        }

        return redirect()->route('front.delete.get')->withInput()->withErrors('Something wrong happened, please try again later!');
    }
}
