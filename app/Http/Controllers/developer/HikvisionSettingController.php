<?php

namespace App\Http\Controllers\developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HikvisionSettingController extends Controller
{
    public function index()
    {
        return view('developer.hikvision-settings');
    }

    public function save(Request $request)
    {
        $request->validate([
            'ip' => ['required', 'ip'],
        ]);

        $ip = $request->ip;
        $url = "http://{$ip}:80";

        $this->setEnvValue('HIKVISION_BASE_URL', $url);

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()
            ->back()
            ->with('success', 'Hikvision Base URL updated successfully.');
    }

    private function setEnvValue($key, $value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return;
        }

        $env = file_get_contents($path);

        if (preg_match("/^{$key}=.*/m", $env)) {
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $env
            );
        } else {
            $env .= "\n{$key}={$value}";
        }

        file_put_contents($path, $env);
    }
}
