<?php

namespace App\Exports;

use App\Constant\GlobalConstant;
use App\Models\LinkFollow;
use App\Models\UserLink;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LinkFollowExport implements FromCollection, WithHeadings, WithColumnWidths, WithMapping
{
    public function __construct(private array $condition)
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->getAll($this->condition));
    }

    public function map($d): array
    {
        return [
            $d->id,
            $d->name,
            $d->email,
            $d->created_at,
            $d->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            'Data cuối',
            'Ngày tạo',
            'Tài khoản',
            'Tên bài',
            'Nội dung',
            'Bình luận',
            'Data',
            'Cảm xúc',
            'Quét',
            'Note',
            'Phòng ban'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
        ];
    }

    public function getAll($condition = [])
    {
        $comment_from = $condition['comment_from'] ?? '';
        $comment_to = $condition['comment_to'] ?? '';
        $delay_from = $condition['delay_from'] ?? '';
        $delay_to = $condition['delay_to'] ?? '';
        $data_from = $condition['data_from'] ?? '';
        $data_to = $condition['data_to'] ?? '';
        $reaction_from = $condition['reaction_from'] ?? '';
        $reaction_to = $condition['reaction_to'] ?? '';
        $time_from = $condition['time_from'] ?? '';
        $time_to = $condition['time_to'] ?? '';
        $last_data_from = $condition['last_data_from'] ?? '';
        $last_data_to = $condition['last_data_to'] ?? '';
        $from = $condition['from'] ?? '';
        $to = $condition['to'] ?? '';
        $user_id = $condition['user_id'] ?? '';
        $user = $condition['user'] ?? '';
        $note = $condition['note'] ?? '';
        $link_id = $condition['link_id'] ?? '';
        $is_scan = $condition['is_scan'] ?? '';
        $type = (string)($condition['type'] ?? '');
        $link_or_post_id = $condition['link_or_post_id'] ?? '';
        $title = $condition['title'] ?? '';
        $content = $condition['content'] ?? '';
        $status = $condition['status'] ?? '';
        $ids = $condition['ids'] ?? [];

        $query = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(updated_at) * 60 - MINUTE(updated_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), updated_at) * 24';
        $queryLastData = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(created_at) * 60 - MINUTE(created_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), created_at) * 24';

        // DB::enableQueryLog();

        $userLinks = UserLink::with(['link.commentLinks.comment', 'user'])
            // title
            ->when(count($ids), function ($q) use ($ids) {
                return $q->whereHas('link', function ($q) use ($ids) {
                    $q->whereIn('id', $ids);
                });
            })
            // title
            ->when($title, function ($q) use ($title) {
                return $q->whereHas('link', function ($q) use ($title) {
                    $q->where('title', 'like', "%$title%");
                });
            })
            // link_or_post_id
            ->when($link_or_post_id, function ($q) use ($link_or_post_id) {
                return $q->whereHas('link', function ($q) use ($link_or_post_id) {
                    $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                });
            })
            // content
            ->when($content, function ($q) use ($content) {
                return $q->whereHas('link', function ($q) use ($content) {
                    $q->where('content', 'like', "%$content%");
                });
            })
            ->when($user_id, function ($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->when($link_id, function ($q) use ($link_id) {
                return $q->where('link_id', $link_id);
            })
            // delay
            ->when(strlen($delay_from), function ($q) use ($delay_from, $delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_from, $delay_to) {
                    return $q->whereHas('link', function ($q) use ($delay_from, $delay_to) {
                        $q->whereRaw('delay >= ?', $delay_from)
                            ->whereRaw('delay <= ?', $delay_to);
                    });
                }, function ($q) use ($delay_from) {
                    return $q->whereHas('link', function ($q) use ($delay_from) {
                        $q->whereRaw('delay >= ?', $delay_from);
                    });
                });
            }, function ($q) use ($delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_to) {
                    return $q->whereHas('link', function ($q) use ($delay_to) {
                        $q->whereRaw('delay <= ?', $delay_to);
                    });
                });
            })
            // data
            ->when(strlen($data_from), function ($q) use ($data_from, $data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_from, $data_to) {
                    return $q->whereHas('link', function ($q) use ($data_from, $data_to) {
                        $q->whereRaw('diff_data >= ?', $data_from)
                            ->whereRaw('diff_data <= ?', $data_to);
                    });
                }, function ($q) use ($data_from) {
                    return $q->whereHas('link', function ($q) use ($data_from) {
                        $q->whereRaw('diff_data >= ?', $data_from);
                    });
                });
            }, function ($q) use ($data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_to) {
                    return $q->whereHas('link', function ($q) use ($data_to) {
                        $q->whereRaw('diff_data <= ?', $data_to);
                    });
                });
            })
            // reaction
            ->when(strlen($reaction_from), function ($q) use ($reaction_from, $reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_from, $reaction_to) {
                    return $q->whereHas('link', function ($q) use ($reaction_from, $reaction_to) {
                        $q->whereRaw('diff_reaction >= ?', $reaction_from)
                            ->whereRaw('diff_reaction <= ?', $reaction_to);
                    });
                }, function ($q) use ($reaction_from) {
                    return $q->whereHas('link', function ($q) use ($reaction_from) {
                        $q->whereRaw('diff_reaction >= ?', $reaction_from);
                    });
                });
            }, function ($q) use ($reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                    return $q->whereHas('link', function ($q) use ($reaction_to) {
                        $q->whereRaw('diff_reaction <= ?', $reaction_to);
                    });
                });
            })
            // comment
            ->when(strlen($comment_from), function ($q) use ($comment_from, $comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_from, $comment_to) {
                    return $q->whereHas('link', function ($q) use ($comment_from, $comment_to) {
                        $q->whereRaw('diff_comment >= ?', $comment_from)
                            ->whereRaw('diff_comment <= ?', $comment_to);
                    });
                }, function ($q) use ($comment_from) {
                    return $q->whereHas('link', function ($q) use ($comment_from) {
                        $q->whereRaw('diff_comment >= ?', $comment_from);
                    });
                });
            }, function ($q) use ($comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_to) {
                    return $q->whereHas('link', function ($q) use ($comment_to) {
                        $q->whereRaw('diff_comment <= ?', $comment_to);
                    });
                });
            })
            // last data
            ->when(strlen($last_data_from), function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                return $q->when(strlen($last_data_to), function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                    return $q->whereHas('link.commentLinks', function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                        $q->whereRaw("$queryLastData >= ?", $last_data_from)
                            ->whereRaw("$queryLastData <= ?", $last_data_to);
                    });
                }, function ($q) use ($last_data_from, $queryLastData) {
                    return $q->whereHas('link.commentLinks', function ($q) use ($last_data_from, $queryLastData) {
                        $q->whereRaw("$queryLastData >= ?", $last_data_from);
                    });
                });
            }, function ($q) use ($last_data_to, $queryLastData) {
                return $q->when(strlen($last_data_to), function ($q) use ($last_data_to, $queryLastData) {
                    return $q->whereHas('link.commentLinks', function ($q) use ($last_data_to, $queryLastData) {
                        $q->whereRaw("$queryLastData <= ?", $last_data_to);
                    });
                });
            })
            // data update count
            ->when(strlen($time_from), function ($q) use ($time_from, $time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_from, $time_to, $query) {
                    return $q->whereHas('link', function ($q) use ($time_from, $time_to, $query) {
                        $q->whereRaw("$query >= ?", $time_from)
                            ->whereRaw("$query <= ?", $time_to);
                    });
                }, function ($q) use ($time_from, $query) {
                    return $q->whereHas('link', function ($q) use ($time_from, $query) {
                        $q->whereRaw("$query >= ?", $time_from);
                    });
                });
            }, function ($q) use ($time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_to, $query) {
                    return $q->whereHas('link', function ($q) use ($time_to, $query) {
                        $q->whereRaw("$query <= ?", $time_to);
                    });
                });
            })
            // date
            ->when($from, function ($q) use ($from, $to) {
                return $q->when($to, function ($q) use ($from, $to) {
                    return $q->whereHas('link', function ($q) use ($from, $to) {
                        $q->whereRaw('created_at >= ?', $from)
                            ->whereRaw('created_at <= ?', $to . ' 23:59:59');
                    });
                }, function ($q) use ($from) {
                    return $q->whereHas('link', function ($q) use ($from) {
                        $q->whereRaw('created_at >= ?', $from);
                    });
                });
            }, function ($q) use ($to) {
                return $q->when($to, function ($q) use ($to) {
                    return $q->whereHas('link', function ($q) use ($to) {
                        $q->whereRaw('created_at <= ?', $to . ' 23:59:59');
                    });
                });
            })
            // is_scan
            ->when(is_numeric($is_scan) || is_array($is_scan), function ($q) use ($is_scan) {
                return $q->whereHas('link', function ($q) use ($is_scan) {
                    switch (true) {
                        case is_array($is_scan):
                            $q->whereIn('is_scan', $is_scan);
                            break;
                        default:
                            $q->where('is_scan', $is_scan);
                            break;
                    }
                });
            })
            // user
            ->when($user, function ($q) use ($user) {
                return $q->whereHas('user', function ($q) use ($user) {
                    $q->where('name', 'like', "%$user%")
                        ->orWhere('email', 'like', "%$user%");
                });
            })
            // note
            ->when($note, function ($q) use ($note) {
                return $q->whereHas('link', function ($q) use ($note) {
                    $q->where('note', 'like', "%$note%");
                });
            })
            // type
            ->when(in_array($type, GlobalConstant::LINK_STATUS), function ($q) use ($type) {
                return $q->whereHas('link', function ($q) use ($type) {
                    $q->where('type', $type);
                });
            })
            // status
            ->when(strlen($status), function ($q) use ($status) {
                return $q->whereHas('link', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->orderByDesc('created_at')
            ->get()?->toArray() ?? [];

        // dd(DB::getRawQueryLog());

        return array_map(function ($value) {
            return [
                ...$value,
                'accounts' => UserLink::with(['link', 'user'])
                    ->whereHas('link', function ($q) use ($value) {
                        $q->where('link_or_post_id', $value['link']['link_or_post_id']);
                    })->get()
            ];
        }, $userLinks);
    }
}
