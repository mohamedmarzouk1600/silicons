<?php
/**
 * @author Mostafa Naguib <mnaguib@tikshif.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact http://www.max-dev.com/Mostafa.Naguib
 */

namespace MaxDev\Modules\Api;

use MaxDev\Modules\APIController;
use MaxDev\Models\ContactEmail;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use MaxDev\Modules\Api\Resource\AddressResource;

class QrController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scan(Request $request)
    {
        ContactEmail::where('qr',$request->qr)->update(['scan_qr'=>1]);
        return $this->respondWithoutError([], 'Done');
    }
}
