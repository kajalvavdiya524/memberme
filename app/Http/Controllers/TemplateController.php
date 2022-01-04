<?php

namespace App\Http\Controllers;

use App\Organization;
use App\OrganizationCardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{

    public function store(Request $request)
    {
        /* @var
         * $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $allowedImageExtensionArray = ['jpeg', 'bmp', 'png','JPG'];

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'template' => 'mimes:' . implode(",", $allowedImageExtensionArray) . '|max:8000',
            'label' => 'string',
            'id' => 'exists:organization_card_templates,id'
        ];
        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {
                $file = $request->file('template');
                if($file){
                    $name = $file->getClientOriginalName();
                    $name = md5($name).'.'.$file->getClientOriginalExtension();
                    $path = '/templates/'.$name;
                    Storage::put($path,File::get($file->getRealPath()));
                    $url =  Storage::disk('local')->url($path);
                }

                if(isset($request->id) && !empty($request->id)){
                    $template = $organization->templates()->find($request->id);
                }

                if(empty($template)){
                    $template = new OrganizationCardTemplate();
                }

                $template->organization_id = $organization->id;
                $template->label = $request->get('label');
                $template->url = !empty($url)?$url:$template->url;
                $template->style = $request->get('style');
                $template->show_image = $request->get('show_image');
                $template->element_labels = $request->get('element_labels');
                $template->coordinates = $request->get('coordinates');
                $template->save();
                $template->refresh();
                return api_response($template);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }

    }

    public function getList(Request $request)
    {
        /* @var $organization Organization */
        $organization =  $request->get(Organization::NAME);
        $templates = $organization->templates()->orderBy('organization_card_templates.id' ,'desc')->limit(3)->get();
        return api_response($templates);
    }
}
