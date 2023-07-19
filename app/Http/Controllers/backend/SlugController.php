<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\Models\Slug;
use App\Models\Slugcategory;

class SlugController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }

    public function list(Request $request){

        $data['title'] = Config::get('constants.PROJECT_NAME') . ' || Slug list';
        $data['description'] = Config::get('constants.PROJECT_NAME') . ' || Slug list';
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . ' || Slug list';
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
            'plugins/custom/datatables/datatables.bundle.css'
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'plugins/custom/datatables/datatables.bundle.js',
            'pages/crud/datatables/data-sources/html.js'
        );
        $data['js'] = array(
            'comman_function.js',
            'slug.js',
        );
        $data['funinit'] = array(
            'Slug.init()'
        );
        $data['header'] = array(
            'title' => 'Slug list',
            'breadcrumb' => array(
                'Dashboard' => route('my-dashboard'),
                'Slug list' => 'Slug list',
            )
        );
        return view('backend.pages.slugs.list', $data);

    }

    public function add (){
        $objSlugcategory = new Slugcategory();
        $data['slug_category'] = $objSlugcategory->get_slug_category();

        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Slug";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Slug";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Slug";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'pages/crud/forms/widgets/select2.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'slug.js',
        );
        $data['funinit'] = array(
            'Slug.add()'
        );
        $data['header'] = array(
            'title' => 'Add slug',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Slug List' => route('slug.list'),
                'Add slug' => 'Add slug',
            )
        );
        return view('backend.pages.slugs.add', $data);
    }

    public function saveAdd(Request $request){
        $objSlug = new Slug();
        $result = $objSlug->saveAdd($request);
        if ($result == "added") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Slug details successfully added.';
            $return['redirect'] = route('slug.list');
        } elseif ($result == "slug_exists") {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Slug has already exists.';
        }  else{
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function edit ($editId){

        $objSlugcategory = new Slugcategory();
        $data['slug_category'] = $objSlugcategory->get_slug_category();

        $objSlug = new Slug();
        $data['slug_details'] = $objSlug->get_slug_details($editId);

        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit Slug";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit Slug";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit Slug";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'pages/crud/forms/widgets/select2.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'slug.js',
        );
        $data['funinit'] = array(
            'Slug.edit()'
        );
        $data['header'] = array(
            'title' => 'Edit slug',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Slug List' => route('slug.list'),
                'Edit slug' => 'Edit slug',
            )
        );
        return view('backend.pages.slugs.edit', $data);
    }

    public function saveEdit(Request $request){
        $objSlug = new Slug();
        $result = $objSlug->saveEdit($request);
        if ($result == "added") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Slug details successfully updated.';
            $return['redirect'] = route('slug.list');
        } elseif ($result == "slug_exists") {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Slug has already exists.';
        }  else{
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function ajaxcall(Request $request){
        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $objSlug = new Slug();
                $list = $objSlug->getdatatable();

                echo json_encode($list);
                break;

            case 'common-activity':
                $data = $request->input('data');
                $objSlug = new Slug();
                $result = $objSlug->common_activity($data);
                if ($result) {
                    $return['status'] = 'success';
                    if($data['activity'] == 'delete-records'){
                        $return['message'] = "Slug details successfully deleted.";
                    }elseif($data['activity'] == 'active-records'){
                        $return['message'] = "Slug details successfully actived.";
                    }else{
                        $return['message'] = "Slug details successfully deactived.";
                    }
                    $return['redirect'] = route('slug.list');
                } else {
                    $return['status'] = 'error';
                    $return['jscode'] = '$("#loader").hide();';
                    $return['message'] = 'It seems like something is wrong';;
                }

                echo json_encode($return);
                exit;
        }
    }
}
