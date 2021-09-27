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
     * @return int
     */
    public function createDocument()
    {
        $address = [
            'address' => 'Uttara, Dhaka - 1230',
            'city'    => 'Dhaka',
            'zipcode' => '1230',
            'user_id' => 'ab42fbabab8ae9f6aa07b14167052b79',
            'type'    => 'address',
        ];

        $uuid = Http::get($this->url . '_uuids')->json()['uuids'][0];

        $payload = json_encode($address);

        $response = Http::withBody($payload, 'application/json')
            ->put($this->url . $this->db . '/' . $uuid);

        return $response->status();
    }

    public function getUUIDs()
    {
        $response = Http::get($this->url . '_uuids');
//        dd($response->json()['uuids'][0]);
        return $response->json()['uuids'][0];
    }

    public function getDocument()
    {
        $documentID = 'ab42fbabab8ae9f6aa07b14167052b79';
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
        $documentID = 'ab42fbabab8ae9f6aa07b1416702ea5e';
        $revision   = "5-e86d2f893e32ef71ffc54f05d8e8b1d1";
        $response   = Http::delete($this->url . $this->db . '/' . $documentID . '?rev=' . $revision);
        return $response->json();
    }

    public function attachFile()
    {

        $documentID = 'akash';

        $revision = '3-008f10aa48a31ec720d375cb4b2f77eb';

        $attachment = 'Untitled document.docx';
        $path       = '/home/mishajib/Downloads/';

        $fileInfo    = finfo_open(FILEINFO_MIME_TYPE);
        $contentType = finfo_file($fileInfo, $path . $attachment);
        dd($contentType);

        $payload = file_get_contents($path . $attachment);

        $response = Http::withBody($payload, $contentType)
            ->put($this->url . $this->db . '/' . $documentID . '/' . $attachment . '?rev=' . $revision);

        return $response->json();
    }

    public function test()
    {

        $documents['docs'] = [
            [
                "_id"      => Http::get($this->url . '_uuids')->json()['uuids'][0],
                "servings" => 4,
                "subtitle" => "Delicious with freshly baked bread",
                "title"    => "FishStew",
                'type'     => 'bulk',
            ],
            [
                "_id"      => Http::get($this->url . '_uuids')->json()['uuids'][0],
                "servings" => 6,
                "subtitle" => "Serve with a whole meal scone topping",
                "title"    => "LambStew",
                'type'     => 'bulk',
            ],
            [
                "_id"      => Http::get($this->url . '_uuids')->json()['uuids'][0],
                "servings" => 8,
                "subtitle" => "Hand-made dumplings make a great accompaniment",
                "title"    => "BeefStew",
                'type'     => 'bulk',
            ]
        ];


        $payload = json_encode($documents);

        // bulk get
        $response = Http::withBody($payload, 'application/json')
            ->post($this->url . $this->db . '/_bulk_docs');

        // bulk insert docs
        /*$response = Http::get($this->url . '/_all_dbs');*/

        return $response->json();
    }

    public function findDocument()
    {
        $documents = [
            "selector"        => [
                "type" => ['$eq' => 'user']
            ],
            "fields"          => ["_id", "first_name", "_rev", "last_name", "type"],
            "execution_stats" => true
        ];


        $payload = json_encode($documents);


        // find by query
        $response = Http::withBody($payload, 'application/json')
            ->post($this->url . $this->db . '/_find');

        return $response->json();
    }
}
