<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CouchDbController extends Controller
{
    private $url, $db;

    public function __construct()
    {
        $this->db  = 'testdb';
        $this->url = 'http://admin:password@127.0.0.1:8954/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::get($this->url . '/_all_dbs');
        return $response->json();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDocument()
    {
        $customer = [
            'first_name' => 'Akash',
            'last_name'  => 'Khan',
            'username'   => 'akash',
            'email'      => 'akash@gmail.com',
            'pass'       => 'password',
        ];

        $payload = json_encode($customer);

        $response = Http::withBody($payload, 'json')
            ->put($this->url . $this->db . '/' . $customer['username']);

        return $response->json();
    }

    public function getUUIDs()
    {
        $response = Http::get($this->url . '_uuids');
        return $response->json();
    }

    public function getDocument()
    {
        $documentID = 'mishajib';
        $response   = Http::get($this->url . $this->db . '/' . $documentID);
        return $response->json();
    }

    public function getAllDocuments()
    {
        $response = Http::get($this->url . $this->db . '/' . '_all_docs');
        return $response->json();
    }

    public function updateDocument()
    {
        $customer = [
            'first_name' => 'MI',
            'last_name'  => 'SHAJIB',
            'username'   => 'mishajib',
            'email'      => 'mishajib2@gmail.com',
            'pass'       => 'password',
        ];

        $customer['_rev'] = '1-be8de8fbacad8539dff3f35d8cf855b0';

        $payload = json_encode($customer);

        $response = Http::withBody($payload, 'json')
            ->put($this->url . $this->db . '/' . $customer['username']);

        return $response->json();
    }

    public function deleteDocument()
    {
        $documentID = 'akash';
        $revision   = "1-629e1601f6503cea9f93ed8010692827";
        $response   = Http::delete($this->url . $this->db . '/' . $documentID . '?rev=' . $revision);
        return $response->json();
    }

    public function attachFile()
    {

        $documentID = 'akash';

        $revision = '3-008f10aa48a31ec720d375cb4b2f77eb';

        $attachment = 'Profile.pdf';
        $path       = '/home/mishajib/Downloads/';

        $fileInfo    = finfo_open(FILEINFO_MIME_TYPE);
        $contentType = finfo_file($fileInfo, $path . $attachment);

        $payload = file_get_contents($path . $attachment);

        $response = Http::withBody($payload, $contentType)
            ->put($this->url . $this->db . '/' . $documentID . '/' . $attachment . '?rev=' . $revision);

        return $response->json();
    }

    public function test()
    {
        $documents['docs'] = [
            [
                "_id"      => "FishStew",
                "servings" => 4,
                "subtitle" => "Delicious with freshly baked bread",
                "title"    => "FishStew"
            ],
            [
                "_id"      => "LambStew",
                "servings" => 6,
                "subtitle" => "Serve with a whole meal scone topping",
                "title"    => "LambStew"
            ],
            [
                "servings" => 8,
                "subtitle" => "Hand-made dumplings make a great accompaniment",
                "title"    => "BeefStew"
            ]
        ];


        $payload = json_encode($documents);

        // bulk get
        /*$response = Http::withBody($payload, 'application/json')
            ->post($this->url . $this->db . '/_bulk_get');*/

        // bulk insert docs
        $response = Http::withBody($payload, 'application/json')
            ->post($this->url . $this->db . '/_bulk_docs');

        return $response->json();
    }

    public function findDocument()
    {
        $documents = [
            "selector"        => [
                "serving" => ['$eq' => 4]
            ],
            "fields"          => ["_id", "_rev", "servings", "title"],
            "sort"            => [["_id" => "asc"]],
            "limit"           => 2,
            "skip"            => 0,
            "execution_stats" => true
        ];


        $payload = json_encode($documents);


        // find by query
        $response = Http::withBody($payload, 'application/json')
            ->post($this->url . $this->db . '/_find');

        return $response->json();
    }
}
