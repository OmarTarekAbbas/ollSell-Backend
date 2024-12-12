<?php

namespace Modules\Report\Http\Controllers\Dropshipper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Report\Entities\Newsletter;
//todo change
class NewsletterController extends BasicController
{
    /**
     * The function "newsletter" validates and saves a new email address to the newsletter database
     * table.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains all the data and information about the request, such as
     * the request method, headers, and input data.
     * 
     * return a response. If the validation passes and the newsletter is successfully saved, it will
     * return a response with the newsletter data and a success message. If there are validation
     * errors, it will return a response with the validation errors.
     */
    public function newsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->passes()) {
            $newsletter = new Newsletter();
            $newsletter->email = $request->email;
            if ($newsletter->save()) {
                return $this->createResponse($newsletter, 'A Newsletter has added successfully');
            }
        }
        return $this->unKnowError($validator->errors());
    }
}
