<?php

namespace App\Http\Controllers;

use DOMDocument;
use Illuminate\Http\Request;
use SimpleXMLElement;

class GenFileController extends Controller
{
    public function index()
    {
        return view('genfile.index');
    }

    public function store(Request $request)
    {
        if ($_POST['type'] == 'xml') {
            $xml = simplexml_load_file(resource_path().'/assets/YYYYMMDDbenpaobaxiogndi3.xml');
            $xml->addAttribute('notification_email', $_POST['Notification']);
            $xml->addAttribute('channel', $_POST['Channel']);

            foreach ($xml->children() as $child) {
                if ($child->getName() == 'asset') {
                    $child->addChild('title', htmlspecialchars($_POST['VideoTitleChs'], ENT_QUOTES));
                    $child->addChild('url', $_POST['URL']);
                }
                if ($child->getName() == 'file') {
                    foreach ($child->attributes() as $name => $value) {
                        if ($name == 'type' && $value == 'video') {
                            $child->addChild('filename', $_POST['VideoFile']);
                            if ($_POST['PublishImmediately'] == 'yes') {
                                $child->addAttribute('urgent_reference', 'True');
                            }
                        } elseif ($name == 'type' && $value == 'image') {
                            if (!empty($_POST['Thumbnail'])) {
                                $child->addChild('filename', $_POST['Thumbnail']);
                            }
                        }
                    }
                }
                if ($child->getName() == 'video') {
                    foreach ($child->children() as $subchild) {
                        if ($subchild->getName() == 'localized_info') {
                            foreach ($subchild->attributes() as $name => $value) {
                                if ($name == 'lang' && $value == 'zh-Hans') {
                                    $ret = $this->handleVideo($subchild, 'Chs');
                                    if ($ret == -1) {
                                        echo "error: title exceed 100 characters";
                                        exit;
                                    } elseif ($ret == -2) {
                                        echo "error: des exceed 5000 characters";
                                        exit;
                                    } elseif ($ret == -3) {
                                        echo "error: keywords exceed 500 characters";
                                        exit;
                                    }
                                }
                            }
                        }
                    }
                    if ($_POST['PublishImmediately'] == 'yes') {
                        $child->addChild('public', 'True');
                    } else {
                        $child->addChild('public', 'False');
                    }
                    if ($_POST['NotifySubscribers'] == 'no') {
                        $child->addChild('notify_subscribers', 'False');
                    }
                    if (!empty($_POST['Thumbnail'])) {
                        $artwork = $child->addChild('artwork');
                        $artwork->addAttribute('type', 'custom_thumbnail');
                        $artwork->addAttribute('path', "/feed/file[@tag='YYYYMMDDvideoHD.jpg']");
                    }
                    if (!empty($_POST['VideoTitleCht'])) {
                        $locale = $child->addChild('localized_info');
                        $locale->addAttribute('lang', 'zh-Hant');
                        $ret = $this->handleVideo($locale, 'Cht');
                        if ($ret == -1) {
                            echo "error: title exceed 100 characters";
                            exit;
                        } elseif ($ret == -2) {
                            echo "error: des exceed 5000 characters";
                            exit;
                        } elseif ($ret == -3) {
                            echo "error: keywords exceed 500 characters";
                            exit;
                        }
                    }
                    if (!empty($_POST['VideoTitleEn'])) {
                        $locale = $child->addChild('localized_info');
                        $locale->addAttribute('lang', 'en');
                        $ret = $this->handleVideo($locale, 'En');
                        if ($ret == -1) {
                            echo "error: title exceed 100 characters";
                            exit;
                        } elseif ($ret == -2) {
                            echo "error: des exceed 5000 characters";
                            exit;
                        } elseif ($ret == -3) {
                            echo "error: keywords exceed 500 characters";
                            exit;
                        }
                    }
                }
                if ($child->getName() == 'video_breaks') {
                    $breaks = explode("\r\n", trim($_POST['AdBreak']));
                    foreach ($breaks as $break) {
                        $breakItem = $child->addChild('break');
                        $breakItem->addAttribute('time', $break);
                    }
                }
                if ($child->getName() == 'playlist') {
                    $child->addAttribute('id', $_POST['Playlist']);
                    $child->addAttribute('channel', $_POST['Channel']);
                }
                if ($child->getName() == 'claim') {
                    $child->addAttribute('rights_policy', "/external/rights_policy[@name='" . $_POST['UsagePolicy'] . "']");
                }
                if (!empty($_POST['MatchPolicy'])) {
                    if ($child->getName() == 'relationship') {
                        foreach ($child->attributes() as $name => $value) {
                            if ($name == 'tag' && $value == 'video_relationship') {
                                $relateItem = $child->addChild('related_item');
                                $relateItem->addAttribute('path', "/feed/asset[@tag='YYYYMMDDvideoHD.ts']");
                            }
                        }
                    }
                }
            }

            if (!empty($_POST['MatchPolicy'])) {
                $relationShip = $xml->addChild('relationship');
                $itemPath = $relationShip->addChild('item');
                $itemPath->addAttribute('path', "/feed/rights_admin[@type='match']");
                $itemPath2 = $relationShip->addChild('item');
                $itemPath2->addAttribute('path', "/external/rights_policy[@name='" . $_POST['MatchPolicy'] . "']");
                $itemRelate = $relationShip->addChild('related_item');
                $itemRelate->addAttribute('path', "/feed/asset[@tag='YYYYMMDDvideoHD.ts']");
            }

            if (!empty($_POST['Thumbnail'])) {
                $file = $xml->addChild('file');
                $file->addAttribute('type', 'image');
                $file->addAttribute('tag', 'YYYYMMDDvideoHD.jpg');
                $file->addChild('filename', $_POST['Thumbnail']);
            }

            $info = pathinfo($_POST['VideoFile']);
            $xml_dir = storage_path('app') . '/xml/';
            if (is_dir($xml_dir) == false) {
                \Storage::makeDirectory('xml');
            }
            if (array_key_exists('extension', $info)) {
                $filename = $xml_dir . basename($_POST['VideoFile'], '.' . $info['extension']) . '.xml';
            } else {
                $filename = $xml_dir . $_POST['VideoFile'] . '.xml';
            }
            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            //echo $dom->saveXML();
            $dom->save($filename);

            header("Cache-Control: max-age=0");
            header("Content-Description: File Transfer");
            header('Content-disposition: attachment; filename=' . basename($filename));
            header("Content-Transfer-Encoding: binary");
            header('Content-Length: ' . filesize($filename));
            @readfile($filename);
        } elseif ($_POST['type'] == 'csv1') {
            $breaks = implode('|', explode("\r\n", trim($_POST['AdBreak'])));
            $keywords = implode('|', explode("\r\n", trim($_POST['VideoKeywordsChs'])));
            $asset_labels = implode('|', explode("\r\n", trim($_POST['asset_labels'])));
            $data = [
                [$_POST['VideoFile'], $_POST['Channel'], $asset_labels, $_POST['VideoTitleChs'], $_POST['VideoDesChs'], $keywords, 'zh-cn', 'Entertainment', $_POST['PublishImmediately'] == 'yes' ? 'public' : 'private', $_POST['NotifySubscribers'] == 'yes' ? 'yes' : 'no', $_POST['Thumbnail'], '', '', $_POST['UsagePolicy'], empty($_POST['MatchPolicy']) ? '' : 'yes', '', $_POST['MatchPolicy'], $breaks, $_POST['Playlist']],
            ];
            $header_data = [
                'filename', 'channel', 'add_asset_labels', 'title', 'description', 'keywords', 'spoken_language', 'category', 'privacy', 'notify_subscribers', 'custom_thumbnail', 'ownership', 'block_outside_ownership', 'usage_policy', 'enable_content_id', 'reference_exclusions', 'match_policy',             'ad_break_times', 'playlist_id'
            ];
            $file_name = $_POST['VideoFile'] . '.csv';

            $csv_dir = storage_path('app') . '/csv/';
            if (is_dir($csv_dir) == false) {
                \Storage::makeDirectory('csv');
            }

            $csv_file = fopen($csv_dir . $_POST['VideoFile'] . '.csv', 'w');
            fwrite($csv_file, implode($header_data, ',') . "\n" . implode($data[0], ','));
            fclose($csv_file);
            $this->exportCsv($data, $header_data, $file_name);
        } else {
            $keywords_cht = implode('|', explode("\r\n", trim($_POST['VideoKeywordsCht'])));
            $keywords_en = implode('|', explode("\r\n", trim($_POST['VideoKeywordsEn'])));
            $data_multi_lang = [
                ['', 'No', 'en-US', $_POST['VideoTitleEn'], $_POST['VideoDesEn'], $keywords_en],
                ['', 'No', 'zh-Hant', $_POST['VideoTitleCht'], $_POST['VideoDesCht'], $keywords_cht],
            ];
            $header_data_multi_lang = [
                'video_id', 'is_primary_language', 'language', 'title', 'description', 'keywords',
            ];
            $hash = hash('md5', $_POST['VideoTitleEn']);
            $file_name_multi_lang = 'multi-lang-' .$hash. '.csv';

            $csv_dir = storage_path('app') . '/csv/';
            if (is_dir($csv_dir) == false) {
                \Storage::makeDirectory('csv');
            }

            $csv_file_multi_lang = fopen($csv_dir . 'multi-lang-' . $hash . '.csv', 'w');
            fwrite($csv_file_multi_lang, implode($header_data_multi_lang, ',') . "\n" . implode($data_multi_lang[0], ','));
            fclose($csv_file_multi_lang);
            $this->exportCsv($data_multi_lang, $header_data_multi_lang, $file_name_multi_lang);
        }
    }

    private function handleVideo(SimpleXMLElement $e, $lang)
    {
        $titleLength = mb_strlen($_POST['VideoTitle' . $lang], 'UTF-8');
        $desLength = mb_strlen($_POST['VideoDes' . $lang], 'UTF-8');
        if ($titleLength > 100) {
            return -1;
        }
        if ($desLength > 5000) {
            return -2;
        }

        $e->addChild('title', htmlspecialchars($_POST['VideoTitle' . $lang]));
        $e->addChild('description', str_replace("\r\n", "\n", htmlspecialchars($_POST['VideoDes' . $lang], ENT_QUOTES)));
        $kwArr = explode("\r\n", trim($_POST['VideoKeywords' . $lang]));
        $count = 0;
        $kwLength = 0;
        foreach ($kwArr as $keyword) {
            $e->addChild('keyword', $keyword);
            $kwLength += mb_strlen($keyword, 'UTF-8');
            if ($kwLength > 500) {
                return -3;
            }
            $count++;
            if ($count > 29) {
                break;
            }
        }

        return 0;
    }

    private function exportCsv($data = [], $header_data = [], $file_name = '')
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        if (!empty($header_data)) {
            foreach ($header_data as $key => $value) {
                $header_data[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $header_data);
        }
        $num = 0;
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        //逐行取出数据，不浪费内存
        $count = count($data);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $num++;
                //刷新一下输出buffer，防止由于数据过多造成问题
                if ($limit == $num) {
                    ob_flush();
                    flush();
                    $num = 0;
                }
                $row = $data[$i];
                foreach ($row as $key => $value) {
                    $row[$key] = iconv('utf-8', 'gbk', $value);
                }
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
    }
}
