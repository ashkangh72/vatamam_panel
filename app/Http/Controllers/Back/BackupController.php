<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Jobs\CreateBackup;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    protected $activeDisk;

    public function __construct()
    {
        $this->activeDisk = config('general.ftp.active') == 1 ? 'ftp' : 'backup';
    }

    /**
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('backups.index');

        $disk = Storage::disk($this->activeDisk);
        $backups = $disk->allFiles(config('backup.backup.name'));

        foreach ($backups as $key => $backup) {
            $backups[$key] = [];
            $backups[$key]['name'] =  substr($backup, strrpos($backup, '/') + 1);

            $date = explode('-', $backups[$key]['name']);
            $backups[$key]['date'] =  "$date[1]-$date[2]-$date[3] $date[4]:$date[5]:". explode('.', $date[6])[0];
        }

        $backups = collect($backups);

        return view('back.backups.index', compact('backups'));
    }

    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('backups.create');

        CreateBackup::dispatchAfterResponse();

        return response('success');
    }

    /**
     * @param $backup
     * @return StreamedResponse
     * @throws AuthorizationException
     */
    public function download($backup): StreamedResponse
    {
        $this->authorize('backups.download');

        $filename = config('backup.backup.name') . '/' . $backup;

        if (!Storage::disk($this->activeDisk)->exists($filename)) {
            abort(404);
        }

        return Storage::disk($this->activeDisk)->download($filename);
    }

    /**
     * @param $backup
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($backup): Response
    {
        $this->authorize('backups.delete');

        $filename = config('backup.backup.name') . '/' . $backup;

        if (!Storage::disk($this->activeDisk)->exists($filename)) {
            abort(404);
        }

        Storage::disk($this->activeDisk)->delete($filename);
        return response('success');
    }
}
