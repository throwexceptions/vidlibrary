<?php

namespace App\Doodstream;

class DoodstreamAPI
{
    private $api_key = '';

    public function __construct()
    {
        $this->Setup(env('DOOD_API'));
    }

    public function Setup($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Get basic info of your account
     * @param  No parameters required
     */
    public function AccountInfo()
    {
        return $this->api_call('account', 'info', []);
    }

    /**
     * Get report of your account (default last 7 days)
     * @param (Optional) last - Last x days report
     */
    public function AccountReport($last = null)
    {
        $req = [
            'last' => $last,
        ];

        return $this->api_call('account', 'stats', $req);
    }

    /**
     * Get DMCA reported files list (500 results per page)
     * @param  No parameters required
     */
    public function DMCAList()
    {
        return $this->api_call('dmca', 'list', []);
    }

    /**
     * Upload Local File to DoodSteam
     * @param (Required) tempfile - Location of the file's temporary location on the server, called using $_FILES['video']['tmp_name']
     * @param (Required) type - Video Extention, called using $_FILES['video']['type']
     * @param (Required) name - Name you want to save the video with, needs to full name with extention for example :- Video1.mp4
     */
    public function Upload($tempfile, $type, $name)
    {
        $upload = $this->api_call('upload', 'server', []);
        $json   = json_decode($upload, true);

        return $this->post_call($tempfile, $type, $name, $json["result"]);
    }

    /**
     * Copy / Clone your's or other's file
     * @param (Required) file_code - File code of the video you want to copy
     * @param (Optional) fld_id - Folder ID to store inside
     */
    public function Copy($file_code, $fld_id = null)
    {
        $req = [
            'file_code' => $file_code,
            'fld_id'    => $fld_id,
        ];

        return $this->api_call('file', 'clone', $req);
    }

    /**
     * Remote Upload an file using it's direct url (This functions adds the url to the Remote Upload queue of the account)
     * @param (Required) url - URL to remote upload
     * @param (Optional) new_title - Set a custom video title
     */
    public function RUpload($url, $new_title = null)
    {
        $req = [
            'url'       => $url,
            'new_title' => $new_title,
        ];

        return $this->api_call('upload', 'url', $req);
    }

    /**
     * Remote Upload URLs List & Status
     * @param  No parameters required
     */
    public function RUploadList()
    {
        return $this->api_call('urlupload', 'list', []);
    }

    /**
     * Remote Upload File Status
     * @param (Required) file_code - File code of the file in Remote Upload Queue
     */
    public function RUploadStatus($file_code)
    {
        $req = [
            'file_code' => $file_code,
        ];

        return $this->api_call('urlupload', 'status', $req);
    }

    /**
     * Get total & used remote upload slots
     * @param  No parameters required
     */
    public function RUploadSlots()
    {
        return $this->api_call('urlupload', 'slots', []);
    }

    /**
     * Restart Errors In Remote Upload List/Queue
     * @param  No parameters required
     */
    public function RestartErrors()
    {
        $req = [
            'restart_errors' => '1',
        ];

        return $this->api_call('urlupload', 'actions', $req);
    }

    /**
     * Clear All Errors In Remote Upload List/Queue
     * @param  No parameters required
     */
    public function ClearErrors()
    {
        $req = [
            'clear_errors' => '1',
        ];

        return $this->api_call('urlupload', 'actions', $req);
    }

    /**
     * Clear All Pending Files In Remote Upload List/Queue
     * @param  No parameters required
     */
    public function ClearAll()
    {
        $req = [
            'clear_all' => '1',
        ];

        return $this->api_call('urlupload', 'actions', $req);
    }

    /**
     * Remove a Specific File from Remote Upload List/Queue
     * @param (Required) file_code - File code to be removed from Remote Upload List/Queue
     */
    public function DeleteCode($file_code)
    {
        $req = [
            'delete_code' => $file_code,
        ];

        return $this->api_call('urlupload', 'actions', $req);
    }

    /**
     * Create a folder
     * @param (Required) name - Name of the folder to be created
     * @param (Optional) parent_id - Parent folder ID
     */
    public function CreateFolder($name, $parent_id = null)
    {
        $req = [
            'name'      => $name,
            'parent_id' => $parent_id,
        ];

        return $this->api_call('folder', 'create', $req);
    }

    /**
     * Rename a folder
     * @param (Required) fld_id - Folder ID
     * @param (Required) name - New name of the folder
     */
    public function RenameFolder($fld_id, $name)
    {
        $req = [
            'fld_id' => $fld_id,
            'name'   => $name,
        ];

        return $this->api_call('folder', 'rename', $req);
    }


    /**
     * Get List of Videos Uploaded with info-
     * @param (Required) page - Pagination , page number from which results have to shown (1 for the most recent uploads; Ascending Order followed)
     * @param (Required) per_page - Max videos per page (Cannot be more than 200)
     * @param (Optional) fld_id - Show videos inside a specific folder
     */
    public function List($page, $per_page, $fld_id = null)
    {
        $req = [
            'page'     => $page,
            'per_page' => $per_page,
            'fld_id'   => $fld_id,
        ];

        return $this->api_call('file', 'list', $req);
    }

    /**
     * Check status of an uploaded file
     * @param (Required) file_code - File Code
     */
    public function FileStatus($file_code)
    {
        $req = [
            'file_code' => $file_code,
        ];

        return $this->api_call('file', 'check', $req);
    }

    /**
     * Get File Info
     * @param (Required) file_code - File Code
     */
    public function FileInfo($file_code)
    {
        $req = [
            'file_code' => $file_code,
        ];

        return $this->api_call('file', 'info', $req);
    }

    /**
     * Get file splash, single or thumbnail image
     * @param (Required) file_code - File Code
     */
    public function FileImage($file_code)
    {
        $req = [
            'file_code' => $file_code,
        ];

        return $this->api_call('file', 'image', $req);
    }

    /**
     * Rename a File
     * @param (Required) file_code - File Code
     * @param (Required) name - New File Name
     */
    public function FileRename($file_code, $name)
    {
        $req = [
            'file_code' => $file_code,
            'title'     => $name,
        ];

        return $this->api_call('file', 'rename', $req);
    }

    /**
     * Search your files
     * @param (Required) search_term - Search term
     */
    public function Search($search_term)
    {
        $req = [
            'search_term' => $search_term,
        ];

        return $this->api_call('search', 'videos', $req);
    }

    /**
     * Use a custom image for an file's embed link, returns the full embed link
     * @param (Required) code/url - File code of the video OR insert an embed url of the video in the parameter(If using a url, make sure it includes https://, do not pass protected embed link)
     * @param (Required) imgurl - URL of the image you want to set as the splash/single image
     * @param (Optional) protected - Return protected embed url if set this parameter to 1, defaults to Null/Static URL if not set.
     */
    public function CustomEmbedImage($code, $imgurl, $protected = null)
    {
        $embed = $this->get_url($code, $protected);
        if ($embed !== 0 && $embed !== 2) {
            $param  = "?c_poster=";
            $url    = $embed.$param.$imgurl;
            $result = json_encode(['msg' => 'OK', 'status' => 200, 'url' => $url], JSON_UNESCAPED_SLASHES);

            return $result;
        } elseif ($embed == 2) {
            return json_encode(['error' => 'Not found or not your file']);
        } else {
            return json_encode(['error' => 'Incorrect file code or url passed']);
        }
    }

    /**
     * Use remote subitles(custom subtitles) for a embed link
     * @param (Required) code/url - File code of the video OR insert an embed url of the video in the parameter(If using a url, make sure it includes https://, do not pass protected embed link)
     * @param (Required) c1_file - Subtitle URL (srt or vtt)
     * @param (Required) c1_label - Subtitle language or any lable
     */
    public function RemoteSubtitles($code, $c1_file, $c1_label)
    {
        $protected = null;
        $embed     = $this->get_url($code, $protected);
        if ($embed !== 0 && $embed !== 2) {
            $param1 = "?c1_file=";
            $param2 = "&c1_label=";
            $url    = $embed.$param1.$c1_file.$param2.$c1_label;
            $result = json_encode(['msg' => 'OK', 'status' => 200, 'url' => $url], JSON_UNESCAPED_SLASHES);

            return $result;
        } elseif ($embed == 2) {
            return json_encode(['error' => 'Not found or not your file']);
        } else {
            return json_encode(['error' => 'Incorrect file code or url passed']);
        }
    }

    /**
     * Use remote JSON subitles(custom subtitles) for a embed link (Load multiple subtitles via URL in JSON format)
     * @param (Required) code/url - File code of the video OR insert an embed url of the video in the parameter(If using a url, make sure it includes https://, do not pass protected embed link)
     * @param (Required) subtitle_json - Multiple subtitle in JSON format  (Look at https://doodstream.com/api-docs#remote-subtitle-json for example)
     */
    public function RemoteJSONSubtitles($code, $subtitle_json)
    {
        $protected = null;
        $embed     = $this->get_url($code, $protected);
        if ($embed !== 0 && $embed !== 2) {
            $param  = "?subtitle_json=";
            $url    = $embed.$param.$subtitle_json;
            $result = json_encode(['msg' => 'OK', 'status' => 200, 'url' => $url], JSON_UNESCAPED_SLASHES);

            return $result;
        } elseif ($embed == 2) {
            return json_encode(['error' => 'Not found or not your file']);
        } else {
            return json_encode(['error' => 'Incorrect file code or url passed']);
        }
    }


    private function is_setup()
    {
        return (!empty($this->api_key));
    }


    private function get_url($code, $protected = null)
    {
        if (!$this->is_setup()) {
            return ['error' => 'You have not called the Setup function with your api key!'];
        }

        if (strlen($code) == 12) {
            $baseurl = "https://dood.la";
            $json    = $this->api_call('file', 'info', $req = ['file_code' => $code]);
            $result  = json_decode($json, true);
            if ($protected == 1) {
                if ($result["result"][0]["status"] !== "Not found or not your file") {
                    $url = $baseurl.$result["result"][0]["protected_embed"];

                    return $url;
                } else {
                    $error = 2;

                    return $error;
                }
            } else {
                if ($result["result"][0]["status"] !== "Not found or not your file") {
                    $url = $baseurl."/e/".$code;

                    return $url;
                } else {
                    $error = 2;

                    return $error;
                }
            }
        } else {
            if (filter_var($code, FILTER_VALIDATE_URL)) {
                $domains = [
                    'www.doodstream.com',
                    'www.dood.la',
                    'www.dood.so',
                    'www.dood.ws',
                    'doodstream.com',
                    'dood.la',
                    'dood.so',
                    'dood.ws',
                ];
                $parse   = parse_url($code);
                if (in_array($parse["host"], $domains)) {
                    if (strlen($parse["path"]) == 15) {
                        $file_code = substr($parse["path"], 3);
                        if ($protected == 1) {
                            $json   = $this->api_call('file', 'info', $req = ['file_code' => $file_code]);
                            $result = json_decode($json, true);
                            if ($result["result"][0]["status"] !== "Not found or not your file") {
                                $url = "https://".$parse["host"].$result["result"][0]["protected_embed"];

                                return $url;
                            } else {
                                $error = 2;

                                return $error;
                            }
                        } else {
                            return $code;
                        }
                    } else {
                        $error = 0;

                        return $error;
                    }
                } else {
                    $error = 0;

                    return $error;
                }
            } else {
                $error = 0;

                return $error;
            }
        }
    }

    //GET methods call

    private function api_call($path, $cmd, $req = [])
    {
        if (!$this->is_setup()) {
            return ['error' => 'You have not called the Setup function with your api key!'];
        }

        $req['key'] = $this->api_key;

        // Generate the query string
        $urldata = http_build_query($req, '', '&');
        $url     = "https://doodapi.com/api/".$path."/".$cmd."?".$urldata;
        // cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        if ($data !== false) {
            return $data;
        } else {
            return ['error' => 'cURL error: '.curl_error($ch)];
        }
        $path = null;
        $cmd  = null;
        $req  = null;
    }

    //Post call for file uploading

    private function post_call($tempfile, $type, $name, $uploadurl)
    {
        if (!$this->is_setup()) {
            return ['error' => 'You have not called the Setup function with your api key!'];
        }

        $key = $this->api_key;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uploadurl.'?'.$key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        $post = [
            'api_key' => "$key",
            'file'    => curl_file_create($tempfile, $type, $name),
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $data = curl_exec($ch);
        if ($data !== false) {
            return $data;
        } else {
            return ['error' => 'cURL error: '.curl_error($ch)];
        }
    }
}

;

