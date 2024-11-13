<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    protected $url;
    protected $key;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_KEY');
    }

    public function getClient()
    {
        return Http::withHeaders([
            'apikey' => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
        ])->baseUrl($this->url);
    }

    public function getData($table)
    {
        return $this->getClient()->get("/rest/v1/{$table}");
    }

    public function insertData($table, $data)
    {
        return $this->getClient()->post("/rest/v1/{$table}", $data);
    }
    
    // 他のメソッドもここに追加可能
}
