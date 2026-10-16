<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TicketShareController extends Controller
{
    
    public function show($id)
    {
        try {
            // ✅ 1. Get expiry parameter
            $expires = request()->query('expires');
            
            // ✅ 2. Check expiry (2 days validation)
            if ($expires) {
                $expiryTime = (int) $expires;
                $currentTime = time();
                
                if ($currentTime > $expiryTime) {
                    return $this->showErrorPage('⚠️ This link has expired after 2 days. Please request a new one from the ticket owner.');
                }
            } else {
                return $this->showErrorPage('⚠️ This link has expired. Please request a new share link.');
            }
            
            $apiBaseUrl = config('app.api_base_url', 'https://demo.p2ptrack360.com:8888/api');
            $apiUrl = $apiBaseUrl . '/tickets/' . $id;
            
            $response = Http::timeout(30)->get($apiUrl);
            
            if (!$response->successful()) {
                return $this->showErrorPage('API Error: ' . $response->status());
            }
            
            $ticketData = $response->json();
            
            $ticket = null;
            
            if (isset($ticketData['data']) && is_array($ticketData['data'])) {
                $ticket = (object) $ticketData['data'];
            } elseif (isset($ticketData[0]) && is_array($ticketData[0])) {
                $ticket = (object) $ticketData[0];
            } elseif (is_array($ticketData) && isset($ticketData['id'])) {
                $ticket = (object) $ticketData;
            } else {
                return $this->showErrorPage('Invalid ticket data format.');
            }
            
            if (!isset($ticket->id)) {
                return $this->showErrorPage('Ticket ID not found in response.');
            }
            
            return view('show', compact('ticket'));
            
        } catch (\Exception $e) {
            if (view()->exists('ticket.not-found')) {
                return view('ticket.not-found', ['message' => $e->getMessage()]);
            }
            return $this->showErrorPage('Error: ' . $e->getMessage());
        }
    }
    
    public function showGeneralView(Request $request)
    {
        try {
            $id = $request->query('id');
            
            if (!$id) {
                return $this->showErrorPage('Ticket ID is required.');
            }
            
            $apiBaseUrl = config('app.api_base_url', 'https://demo.p2ptrack360.com:8888/api');
            $apiUrl = $apiBaseUrl . '/tickets/' . $id;
            
            $response = Http::timeout(30)->get($apiUrl);
            
            if (!$response->successful()) {
                return $this->showErrorPage('Ticket not found or API error.');
            }
            
            $ticketData = $response->json();
            
            $ticket = null;
            
            if (isset($ticketData['data']) && is_array($ticketData['data'])) {
                $ticket = (object) $ticketData['data'];
            } elseif (isset($ticketData[0]) && is_array($ticketData[0])) {
                $ticket = (object) $ticketData[0];
            } elseif (is_array($ticketData) && isset($ticketData['id'])) {
                $ticket = (object) $ticketData;
            } else {
                return $this->showErrorPage('Invalid ticket data format.');
            }
            
            if (!isset($ticket->id)) {
                return $this->showErrorPage('Ticket not found.');
            }
            
            return view('tickets-view', compact('ticket'));
            
        } catch (\Exception $e) {
            if (view()->exists('ticket.not-found')) {
                return view('ticket.not-found', ['message' => $e->getMessage()]);
            }
            return $this->showErrorPage('Error: ' . $e->getMessage());
        }
    }

    /**
     * Show Error Page (Fallback)
     */
    private function showErrorPage($message)
    {
        return response()->make("
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css' rel='stylesheet'>
            <style>
                body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                .error-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
                .error-card { max-width: 500px; width: 100%; border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
                .error-icon { font-size: 80px; color: #dc3545; margin-bottom: 20px; }
                .btn-custom { padding: 10px 30px; border-radius: 50px; font-weight: 500; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <div class='card error-card'>
                    <div class='card-body p-5 text-center'>
                        <div class='error-icon'>
                            <i class='bi bi-exclamation-triangle'></i>
                        </div>
                        <h3 class='fw-bold mb-3'>Error Loading Ticket</h3>
                        <p class='text-muted mb-4'>$message</p>
                        <div class='d-flex gap-3 justify-content-center flex-wrap'>
                            <a href='" . route('dashboard') . "' class='btn btn-primary btn-custom'>
                                <i class='bi bi-speedometer2 me-2'></i> Dashboard
                            </a>
                            <a href='javascript:history.back()' class='btn btn-outline-secondary btn-custom'>
                                <i class='bi bi-arrow-left me-2'></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ", 404);
    }
}