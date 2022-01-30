<?php

namespace Starmoozie\LaravelLogManager\app\Http\Controllers;

use Starmoozie\LaravelLogManager\app\Classes\LogViewer;
use Illuminate\Routing\Controller;

class LogController extends Controller
{
    /**
     * Lists all log files.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->data['files'] = LogViewer::getFiles(true, true);
        $this->data['title'] = trans('starmoozie::logmanager.log_manager');

        return view('logmanager::logs', $this->data);
    }

    /**
     * Previews a log file.
     *
     * @throws \Exception
     */
    public function preview($file_name)
    {
        LogViewer::setFile(decrypt($file_name));

        $logs = LogViewer::all();

        if (count($logs) <= 0) {
            abort(404, trans('starmoozie::logmanager.log_file_doesnt_exist'));
        }

        $this->data['logs'] = $logs;
        $this->data['title'] = trans('starmoozie::logmanager.preview').' '.trans('starmoozie::logmanager.logs');
        $this->data['file_name'] = decrypt($file_name);

        return view('logmanager::log_item', $this->data);
    }

    /**
     * Downloads a log file.
     *
     * @param $file_name
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file_name)
    {
        return response()->download(LogViewer::pathToLogFile(decrypt($file_name)));
    }

    /**
     * Deletes a log file.
     *
     * @param $file_name
     *
     * @throws \Exception
     *
     * @return string
     */
    public function delete($file_name)
    {
        if (config('starmoozie.logmanager.allow_delete') == false) {
            abort(403);
        }

        if (app('files')->delete(LogViewer::pathToLogFile(decrypt($file_name)))) {
            return 'success';
        }

        abort(404, trans('starmoozie::logmanager.log_file_doesnt_exist'));
    }
}
