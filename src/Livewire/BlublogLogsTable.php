<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\Log;
use Livewire\Component;
use Livewire\WithPagination;


class BlublogLogsTable extends Component
{
    protected $paginationTheme = 'bootstrap';

    use WithPagination;

    public $type = 'visit';
    public $color;
    public function render()
    {
        if ($this->type == 'visit' or $this->type == 'bot') {
            $this->color = 'primary';
        } elseif ($this->type == 'info') {
            $this->color = 'info';
        } elseif ($this->type == 'alert') {
            $this->color = 'warning';
        } else {
            $this->color = 'danger';
        }

        $logs = Log::where([
            ['type', '=', $this->type],
        ])->latest()->paginate(10);
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
