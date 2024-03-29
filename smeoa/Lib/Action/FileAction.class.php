<?php
class FileAction extends CommonAction {
	//过滤查询字段
	function _search_filter(&$map) {
		$map['is_del'] = array('eq', '0');
	}

	public function del() {
		$fileId = f_decode($_POST['id']);
		$File = M("File");
		$File -> id = $fileId;
		$File -> is_del = 1;
		$File -> save();
		if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . C("SAVE_PATH") . $savename)) {
			unlink($_SERVER["DOCUMENT_ROOT"] . "/" . C("SAVE_PATH") . $savename);
			$this -> assign('jumpUrl', get_return_url());
			$this -> success('', "删除成功", 1);
		}
	}

	function player() {
		$user_id = get_user_id();
		$this -> assign("user_id", $user_id);

		$file_id = $_GET['attach_id'];
		$file_id = f_decode($file_id);
		$add_file = explode(";", $add_file);
		$add_file = $add_file[0];
		$where['id'] = $file_id;
		$video_path = M("File") -> where($where) -> getField("savename");
		$video_ext = M("File") -> where($where) -> getField("extension");
		$this -> assign('video_file', C('SAVE_PATH') . $video_path);
		$this -> assign('video_ext', $video_ext);
		$this -> display();
	}

	public function upload() {
		if (!empty($_FILES)) {
			$this -> _upload();
		}
	}

	// 文件上传
	private function _upload() {
		import("@.ORG.Util.UploadFile");
		$module = strtolower($_REQUEST["module"]);
		$upload = new UploadFile();
		$upload -> subFolder = $module;
		$upload -> savePath = C("SAVE_PATH");
		$upload -> saveRule = uniqid;
		$upload -> autoSub = true;
		$upload -> subType = "date";

		if (!$upload -> upload()) {
			$this -> error($upload -> getErrorMsg());
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload -> getUploadFileInfo();
			$File = M("File");
			$File -> create($uploadList[0]);
			$File -> create_time = time();
			$user_id = get_user_id();
			$File -> user_id = $user_id;
			$fileId = $File -> add();

			$fileInfo = $uploadList[0];
			$fileInfo['id'] = $fileId;
			$fileInfo['error'] = 0;
			$fileInfo['url'] = $fileInfo['savepath'] . $fileInfo['savename'];

			//header("Content-Type:text/html; charset=utf-8");
			exit(json_encode($fileInfo));
			//$this->success ('上传成功！');
		}
	}

	public function down($file_id) {

		$file_id = f_decode($file_id);
		$File = M("File") -> find($file_id);

		$filepath = C("SAVE_PATH") . $File['savename'];
		$filePath = realpath($filepath);
		$fp = fopen($filePath, 'rb');
		$ext = $File['ext'];

		//$filePath = realpath($filepath);
		$query = file_get_contents($filepath);
		//$query = file_get_contents($filepath);

		$filetype['chm'] = 'application/octet-stream';
		$filetype['ppt'] = 'application/vnd.ms-powerpoint';
		$filetype['xls'] = 'application/vnd.ms-excel';
		$filetype['doc'] = 'application/msword';
		$filetype['pptx'] = 'application/vnd.ms-powerpoint';
		$filetype['xlsx'] = 'application/vnd.ms-excel';
		$filetype['docx'] = 'application/msword';
		$filetype['exe'] = 'application/octet-stream';
		$filetype['rar'] = 'application/octet-stream';
		$filetype['js'] = "javascript/js";
		$filetype['css'] = "text/css";
		$filetype['hqx'] = "application/mac-binhex40";
		$filetype['bin'] = "application/octet-stream";
		$filetype['oda'] = "application/oda";
		$filetype['pdf'] = "application/pdf";
		$filetype['ai'] = "application/postsrcipt";
		$filetype['eps'] = "application/postsrcipt";
		$filetype['es'] = "application/postsrcipt";
		$filetype['rtf'] = "application/rtf";
		$filetype['mif'] = "application/x-mif";
		$filetype['csh'] = "application/x-csh";
		$filetype['dvi'] = "application/x-dvi";
		$filetype['hdf'] = "application/x-hdf";
		$filetype['nc'] = "application/x-netcdf";
		$filetype['cdf'] = "application/x-netcdf";
		$filetype['latex'] = "application/x-latex";
		$filetype['ts'] = "application/x-troll-ts";
		$filetype['src'] = "application/x-wais-source";
		$filetype['zip'] = "application/zip";
		$filetype['bcpio'] = "application/x-bcpio";
		$filetype['cpio'] = "application/x-cpio";
		$filetype['gtar'] = "application/x-gtar";
		$filetype['shar'] = "application/x-shar";
		$filetype['sv4cpio'] = "application/x-sv4cpio";
		$filetype['sv4crc'] = "application/x-sv4crc";
		$filetype['tar'] = "application/x-tar";
		$filetype['ustar'] = "application/x-ustar";
		$filetype['man'] = "application/x-troff-man";
		$filetype['sh'] = "application/x-sh";
		$filetype['tcl'] = "application/x-tcl";
		$filetype['tex'] = "application/x-tex";
		$filetype['texi'] = "application/x-texinfo";
		$filetype['texinfo'] = "application/x-texinfo";
		$filetype['t'] = "application/x-troff";
		$filetype['tr'] = "application/x-troff";
		$filetype['roff'] = "application/x-troff";
		$filetype['shar'] = "application/x-shar";
		$filetype['me'] = "application/x-troll-me";
		$filetype['ts'] = "application/x-troll-ts";
		$filetype['gif'] = "image/gif";
		$filetype['jpeg'] = "image/pjpeg";
		$filetype['jpg'] = "image/pjpeg";
		$filetype['jpe'] = "image/pjpeg";
		$filetype['ras'] = "image/x-cmu-raster";
		$filetype['pbm'] = "image/x-portable-bitmap";
		$filetype['ppm'] = "image/x-portable-pixmap";
		$filetype['xbm'] = "image/x-xbitmap";
		$filetype['xwd'] = "image/x-xwindowdump";
		$filetype['ief'] = "image/ief";
		$filetype['tif'] = "image/tiff";
		$filetype['tiff'] = "image/tiff";
		$filetype['pnm'] = "image/x-portable-anymap";
		$filetype['pgm'] = "image/x-portable-graymap";
		$filetype['rgb'] = "image/x-rgb";
		$filetype['xpm'] = "image/x-xpixmap";
		$filetype['txt'] = "text/plain";
		$filetype['c'] = "text/plain";
		$filetype['cc'] = "text/plain";
		$filetype['h'] = "text/plain";
		$filetype['html'] = "text/html";
		$filetype['htm'] = "text/html";
		$filetype['htl'] = "text/html";
		$filetype['rtx'] = "text/richtext";
		$filetype['etx'] = "text/x-setext";
		$filetype['tsv'] = "text/tab-separated-values";
		$filetype['mpeg'] = "video/mpeg";
		$filetype['mpg'] = "video/mpeg";
		$filetype['mpe'] = "video/mpeg";
		$filetype['avi'] = "video/x-msvideo";
		$filetype['qt'] = "video/quicktime";
		$filetype['mov'] = "video/quicktime";
		$filetype['moov'] = "video/quicktime";
		$filetype['movie'] = "video/x-sgi-movie";
		$filetype['au'] = "audio/basic";
		$filetype['snd'] = "audio/basic";
		$filetype['wav'] = "audio/x-wav";
		$filetype['aif'] = "audio/x-aiff";
		$filetype['aiff'] = "audio/x-aiff";
		$filetype['aifc'] = "audio/x-aiff";
		$filetype['swf'] = "application/x-shockwave-flash";

		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (!preg_match("/MSIE/", $ua)) {
			header("Content-Length: " . filesize($filePath));
			header("Content-type:" . $filetype[$ext]);
			header("Content-Length: " . filesize($filePath));
			header("Accept-Ranges: bytes");
			header("Accept-Length: " . filesize($filePath));
		}

		header("Content-Disposition:attachment;filename =" . str_ireplace('+', '%20', URLEncode($File['name'])));
		header('Cache-Control:must-revalidate, post-check=0,pre-check=0');
		header('Expires:     0');
		header('Pragma:     public');
		//echo $query;
		fpassthru($fp);
		exit ;
	}

}
