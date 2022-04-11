<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Traits\ApiResponse;


class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::get();
        $documentsResource = DocumentResource::collection( $documents );
        return ApiResponse::successResponseWithData( $documentsResource, 'Documents retrieved', 200 );
    }


    public function store(CreateDocumentRequest $request)
    {
        $data = $request->validated();
        $document = Document::create( $data );
        $documentResource = new DocumentResource( $document );
        return ApiResponse::successResponseWithData( $documentResource, 'Document created', 203 );
    }


    public function show( $code )
    {
        $document = Document::where( 'code', $code )->first();
        if( $document ){
            $documentResource = new DocumentResource( $document );
            return ApiResponse::successResponseWithData( $documentResource, 'Document retrieved', 200 );
        } else {
            return ApiResponse::errorResponse('Document not found', 404 );
        }

    }

    public function update(UpdateDocumentRequest $request, $code)
    {
        $documentToUpdate = Document::where( 'code', $code )->first();
        $data = $request->validated();

        if( $documentToUpdate ){
            $documentToUpdate->update( $data );
            $documentResource = new DocumentResource( $documentToUpdate );
            return ApiResponse::successResponseWithData( $documentResource, 'Document updated', 200 );
        } else{
            return ApiResponse::errorResponse('Document not found', 404 );
        }
    }

    public function destroy( $code )
    {
        $document = Document::where( 'code', $code )->first();
        if( $document ){
            $document->delete();
            return ApiResponse::successResponse('Document deleted', 200 );
        } else {
            return ApiResponse::errorResponse('Document not found', 404 );
        }
    }
}
