<?php

namespace App\Http\Controllers;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientsController extends Controller
{
    protected $elasticParams = [];
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->elasticParams['index'] = env('ES_INDEX');
        $this->elasticParams['type'] = 'clients';
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $name = $request->get('name');
        $estado = $request->get('estado');
        if ($name) {
            $this->elasticParams['body'] = [
                'query' => [
                    'match' => [
                        'name' => $name
                    ],
                ]
            ];
            $this->elasticParams['size'] = 100;
        }
        $clients = $this->client->search($this->elasticParams);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $this->elasticParams['body'] = $data;
        $this->elasticParams['refresh'] = true;
        $this->elasticParams['id'] = (string)RamseyUuid::uuid4();

        $this->client->create($this->elasticParams);

        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        try {
            $this->elasticParams['id'] = $id;
            $client = $this->client->get($this->elasticParams);
        } catch (Missing404Exception $e) {
            throw new NotFoundHttpException("Client not found");
        }
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->elasticParams['id'] = $id;
        if (!$this->client->exists($this->elasticParams)) {
            throw new NotFoundHttpException("Client not found");
        }

        $data = $request->all();
        unset($data['_token']);
        unset($data['_method']);
        $this->elasticParams['refresh'] = true;
        $this->elasticParams['body']['doc'] = $data;
        $this->client->update($this->elasticParams);
        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->elasticParams['id'] = $id;
        if (!$this->client->exists($this->elasticParams)) {
            throw new NotFoundHttpException("Client not found");
        }

        $this->elasticParams['refresh'] = true;
        $this->client->delete($this->elasticParams);
        return redirect()->route('clients.index');
    }
}
