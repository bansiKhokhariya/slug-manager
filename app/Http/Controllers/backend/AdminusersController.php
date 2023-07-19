<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\Models\Adminusers;

class AdminusersController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }

    public function list(Request $request){

        $data['title'] = Config::get('constants.PROJECT_NAME') . ' || Admin users list';
        $data['description'] = Config::get('constants.PROJECT_NAME') . ' || Admin users list';
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . ' || Admin users list';
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
            'adminusers.js',
        );
        $data['funinit'] = array(
            'Adminusers.init()'
        );
        $data['header'] = array(
            'title' => 'Admin users list',
            'breadcrumb' => array(
                'Dashboard' => route('my-dashboard'),
                'Admin users list' => 'Admin users list',
            )
        );
        return view('backend.pages.adminusers.list', $data);


    }

    public function add (){
        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add admin users";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add admin users";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add admin users";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'adminusers.js',
        );
        $data['funinit'] = array(
            'Adminusers.add()'
        );
        $data['header'] = array(
            'title' => 'Add admin users',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Admin users' => route('admin-users.list'),
                'Add admin users' => 'Add admin users',
            )
        );
        return view('backend.pages.adminusers.add', $data);
    }

    public function saveAdd(Request $request){
        $objAdminusers = new Adminusers();
        $result = $objAdminusers->saveAdd($request);
        if ($result == "added") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Admin users details successfully added.';
            $return['redirect'] = route('admin-users.list');
        } elseif ($result == "user_mail_exists") {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'The email address has already been registered.';
        } elseif ($result == "user_phone_number_exists") {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'The phone number has already been registered.';
        } else{
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function edit ($editId){
        $objAdminusers = new Adminusers();
        $data['user_details'] = $objAdminusers->get_admin_users_details($editId);

        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit admin users";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit admin users";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit admin users";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'adminusers.js',
        );
        $data['funinit'] = array(
            'Adminusers.edit()'
        );
        $data['header'] = array(
            'title' => 'Edit admin users',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Admin users' => route('admin-users.list'),
                'Edit admin users' => 'Edit admin users',
            )
        );
        return view('backend.pages.adminusers.edit', $data);
    }

    public function saveEdit(Request $request){
        $objAdminusers = new Adminusers();
        $result = $objAdminusers->saveEdit($request);
        if ($result == "added") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Admin users details successfully updated.';
            $return['redirect'] = route('admin-users.list');
        } elseif ($result == "user_mail_exists") {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'The email address has already been registered.';
        } elseif ($result == "user_phone_number_exists") {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'The phone number has already been registered.';
        } else{
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
                $objAdminusers = new Adminusers();
                $list = $objAdminusers->getdatatable();

                echo json_encode($list);
                break;



            case 'common-activity':
                $objAdminusers = new Adminusers();
                $data = $request->input('data');
                $result = $objAdminusers->common_activity($data);
                if ($result) {
                    $return['status'] = 'success';
                    if($data['activity'] == 'delete-records'){
                        $return['message'] = "Admin user's details successfully deleted.";
                    }elseif($data['activity'] == 'active-records'){
                        $return['message'] = "Admin user's details successfully actived.";
                    }else{
                        $return['message'] = "Admin user's details successfully deactived.";
                    }
                    $return['redirect'] = route('admin-users.list');
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
