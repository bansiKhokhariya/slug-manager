<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
use Route;

class Document extends Model
{
    use HasFactory;
    protected $table = 'document';


    public function saveAdd($requestData)
    {
        $loginUser = Session::all();
        $objSlug = new Document();
        $objSlug->title = $requestData['title'];
        $objSlug->subTitle = $requestData['subTitle'];
        $objSlug->topic = $requestData['topic'];
        $objSlug->subTopic = $requestData['subTopic'];
        $objSlug->content = $requestData['content'];
        $objSlug->reference = $requestData['reference'];
        $objSlug->status = $requestData['status'];
        $objSlug->add_by = $loginUser['logindata'][0]['id'];
        $objSlug->updated_by = $loginUser['logindata'][0]['id'];
        $objSlug->is_deleted = 'N';
        $objSlug->created_at = date('Y-m-d H:i:s');
        $objSlug->updated_at = date('Y-m-d H:i:s');
        if ($objSlug->save()) {
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $objAudittrails->add_audit('Insert', str_replace(".", "/", $currentRoute), json_encode($requestData->input()), 'Documents');
            return 'added';
        }
        return 'wrong';
    }

    public function saveEdit($requestData)
    {

        $loginUser = Session::all();
        $objSlug = Document::find($requestData['editId']);
        $objSlug->title = $requestData['title'];
        $objSlug->subTitle = $requestData['subTitle'];
        $objSlug->topic = $requestData['topic'];
        $objSlug->subTopic = $requestData['subTopic'];
        $objSlug->content = $requestData['content'];
        $objSlug->reference = $requestData['reference'];
        $objSlug->status = $requestData['status'];
        $objSlug->updated_by = $loginUser['logindata'][0]['id'];
        $objSlug->updated_at = date('Y-m-d H:i:s');
        if ($objSlug->save()) {
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $objAudittrails->add_audit('Update', str_replace(".", "/", $currentRoute), json_encode($requestData->input()), 'Documents');
            return 'added';
        }
        return 'wrong';
    }

    public function get_document_details($id)
    {
        return Document::from('document')
            ->join('slugs', 'slugs.id', '=', 'document.subTitle')
            ->join('slugs_category', 'slugs_category.id', '=', 'slugs.category')
            ->select('slugs_category.id as categoryId', 'document.title', 'document.subTitle', 'document.status', 'document.topic', 'document.subTopic', 'document.content', 'document.reference', 'document.id')
            ->where('document.id', $id)
            ->first();
    }
}
