<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    public function forward(Request $request)
    {
        // Validate 'url' parameter in query string
        $url = $request->query('url');
        if (!$url) {
            return response()->json(['error' => 'Missing url parameter'], 400);
        }

        try {
            // Prepare the request
            $http = Http::withOptions([
                'verify' => false,  // Disable SSL verification
            ]);

            // Check if any files are present in the request
            if (!empty($request->allFiles())) {
                $multipart = [];

                // Add files to multipart
                foreach ($request->allFiles() as $key => $file) {
                    // Handle single or multiple files with same key
                    if (is_array($file)) {
                        foreach ($file as $f) {
                            $multipart[] = [
                                'name' => $key . '[]',
                                'contents' => fopen($f->getRealPath(), 'r'),
                                'filename' => $f->getClientOriginalName()
                            ];
                        }
                    } else {
                        $multipart[] = [
                            'name' => $key,
                            'contents' => fopen($file->getRealPath(), 'r'),
                            'filename' => $file->getClientOriginalName()
                        ];
                    }
                }

                // Add other form data
                foreach ($request->except(array_keys($request->allFiles())) as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            $multipart[] = [
                                'name' => $key . '[]',
                                'contents' => $val
                            ];
                        }
                    } else {
                        $multipart[] = [
                            'name' => $key,
                            'contents' => $value
                        ];
                    }
                }

                $response = $http->asMultipart()->post($url, $multipart);
            } else {
                // Normal POST with data
                $response = $http->post($url, $request->all());
            }

            // Return response from external API
            return response($response->body(), $response->status())
                ->header('Content-Type', $response->header('Content-Type') ?? 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get(Request $request)
    {
        $url = $request->query('url');

        if (!$url) {
            return response()->json(['error' => 'Missing url parameter'], 400);
        }

        try {
            $response = Http::withOptions([
                'verify' => false, // Skip SSL verification
            ])->get($url);

            if ($response->successful()) {
                return response($response->body(), $response->status())
                    ->header('Content-Type', $response->header('Content-Type') ?? 'application/json');
            } else {
                return response()->json([
                    'error' => 'Request failed',
                    'status' => $response->status(),
                    'body' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
