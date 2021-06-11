<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\Log;
use Livewire\Component;
use Livewire\WithPagination;


class BlublogLogsTable extends Component
{
    public $type;
    public $color;
    public $search;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
    ];
    use WithPagination;
    public function render()
    {
        if (!$this->type) {
            $this->color = 'light';
        } elseif ($this->type == 'visit' or $this->type == 'bot') {
            $this->color = 'primary';
        } elseif ($this->type == 'info') {
            $this->color = 'info';
        } elseif ($this->type == 'alert') {
            $this->color = 'warning';
        } else {
            $this->color = 'danger';
        }

        if ($this->search) {
            $logs = Log::where([
                ['ip', 'like', '%' . $this->search . '%'],
                ['type', '=', $this->type],
            ])->latest()->paginate(8);
        } else {
            if ($this->type) {
                $logs = Log::where([
                    ['type', '=', $this->type],
                ])->latest()->paginate(10);
            } else {
                $logs = Log::latest()->paginate(10);
            }
        }
        return view('blublog::livewire.blublog-logs-table')->with('logs', $logs);
    }
    public function showOnly($type)
    {
        $this->type = $type;
    }
    public function delete($id)
    {
        $log = Log::find($id);
        if (blublog_is_admin()) {
            $log->delete();
        }
    }
}
