<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\Models\Slugcategory;
use App\Models\Slug;
use App\Models\Document;
use PDF;

class DocumentController extends Controller
{

    function __construct()
    {
        $this->middleware('admin');
    }

    public function list(Request $request)
    {

        $data['title'] = Config::get('constants.PROJECT_NAME') . ' || Document list';
        $data['description'] = Config::get('constants.PROJECT_NAME') . ' || Document list';
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . ' || Document list';
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
            'document.js',
        );
        $data['funinit'] = array(
            'Document.init()'
        );
        $data['header'] = array(
            'title' => 'Document list',
            'breadcrumb' => array(
                'Dashboard' => route('my-dashboard'),
                'Document list' => 'Document list',
            )
        );
        return view('backend.pages.document.list', $data);
    }

    public function add()
    {

        $objSlugcategory = new Slugcategory();
        $data['slug_category'] = $objSlugcategory->get_slug_category();

        $data['title'] = Config::get('constants.PROJECT_NAME') . " || Add Document";
        $data['description'] = Config::get('constants.PROJECT_NAME') . " || Add Document";
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . " || Add Document";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array();
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'document.js',
        );
        $data['funinit'] = array(
            'Document.add()'
        );
        $data['header'] = array(
            'title' => 'Add Document',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Document' => route('document.list'),
                'Add Document' => 'Add Document',
            )
        );
        return view('backend.pages.document.add', $data);
    }

    public function saveAdd(Request $request)
    {
        $objDocument = new Document();
        $result = $objDocument->saveAdd($request);
        if ($result == "added") {
            $return['status'] = 'success';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Document details successfully added.';
            $return['redirect'] = route('document.list');
        } else {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function edit($editId)
    {

        $objDocument = new Document();
        $data['document_details'] = $objDocument->get_document_details($editId);

        $objSlug = new Slug();
        $data['slug'] = $objSlug->get_slug_by_category($data['document_details']->categoryId);

        $objSlugcategory = new Slugcategory();
        $data['slug_category'] = $objSlugcategory->get_slug_category();

        $data['title'] = Config::get('constants.PROJECT_NAME') . " || Edit Document";
        $data['description'] = Config::get('constants.PROJECT_NAME') . " || Edit Document";
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . " || Edit Document";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array();
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'pages/crud/forms/widgets/select2.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'document.js',
        );
        $data['funinit'] = array(
            'Document.edit()'
        );
        $data['header'] = array(
            'title' => 'Edit document',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Document List' => route('document.list'),
                'Edit document' => 'Edit document',
            )
        );
        return view('backend.pages.document.edit', $data);
    }

    public function saveEdit(Request $request)
    {
        $objDocument = new Document();
        $result = $objDocument->saveEdit($request);
        if ($result == "added") {
            $return['status'] = 'success';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Document details successfully updated.';
            $return['redirect'] = route('document.list');
        } else {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function ajaxcall(Request $request)
    {
        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $objDocument = new Document();
                $list = $objDocument->getdatatable();

                echo json_encode($list);
                break;

            case 'change-category':
                $objSlug = new Slug();
                $slug = $objSlug->get_slug_by_category($request->input('data')['category']);
                echo json_encode($slug);
                break;

            case 'common-activity':
                $data = $request->input('data');
                $objDocument = new Document();
                $result = $objDocument->common_activity($data);
                if ($result) {
                    $return['status'] = 'success';
                    if ($data['activity'] == 'delete-records') {
                        $return['message'] = "Documents details successfully deleted.";
                    } elseif ($data['activity'] == 'active-records') {
                        $return['message'] = "Documents details successfully actived.";
                    } else {
                        $return['message'] = "Documents details successfully deactived.";
                    }
                    $return['redirect'] = route('document.list');
                } else {
                    $return['status'] = 'error';
                    $return['jscode'] = '$("#loader").hide();';
                    $return['message'] = 'It seems like something is wrong';;
                }

                echo json_encode($return);
                exit;
        }
    }

    public function generatePdf($id)
    {

        $objDocument = new Document();
        $data['document_details'] = $objDocument->get_document_details($id);
        
        $pdf = PDF::loadView('backend.pages.document.slugPDF', $data);

        return $pdf->download('slug.pdf');
    }
}
