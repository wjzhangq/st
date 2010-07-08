<?php 
/*
 * GD 操作imge 的类
 */

class img{
	var $source = '';
	var $source_type ='';
	var $im;
	var $defalut_type = 'jpg'; //默认图片类型
	var $defalut_quality = 90; //默认jpeg图片质量
	
	/*
	 * 初始化
	 */
	function __construct($path=''){
		if ($path){
			if (is_file($path)){
				$this->open($path);
			}else{
				throw new imgException("{$path} is not a file");
			}
		}
	}
	
	/*
	 * 创建一个空白图像
	 * @$color	如果颜色为空就创建透明的
	 */
	function create($width=100, $height=100, $color="FFFFFF") {
		$this->source_type = $this->defalut_type; //默认jpg
		$this->im = imagecreatetruecolor($width,$height);
		if (!empty($color)) {
			$this->fill($color);
		}else{
			$this->transparent($width, $height);
		}
	}
	
	/*
	 * 将画布填充
	 */
	function fill($color="FFFFFF"){
		$arrColor = self::hex2rgb($color);
		$bgcolor = imagecolorallocate($this->im, $arrColor['red'], $arrColor['green'], $arrColor['blue']);
		imagefill($this->im, 0, 0, $bgcolor);
	}
	
	/*
	 * 创建透明
	 */
	function transparent($width, $height){
		$png  = "iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29m";
		$png .= "dHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBNCg";
		$png .= "dyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAAN";
		$png .= "egcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQ";
		$png .= "oHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAA";
		$png .= "DXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII=";
		$blank = imagecreatefromstring(base64_decode($png));;
		imagesavealpha($this->im,true);
		imagealphablending($this->im,false);
		imagecopyresized($this->im,$blank,0,0,0,0,$width,$height,40,40);
		imagedestroy($blank);		
	}
	
	
	/*
	 * 返回图片宽度
	 */
	function width(){
		return imagesx($this->im);
	}
	
	/*
	 * 返回图片高度
	 */
	function height(){
		return imagesy($this->im);
	}
	
	/*
	 * 调整图片大小, 高度和宽度有一个可以为空
	 */
	function resize($width=0, $height=0){
		if (empty($width) && empty($height)){throw new imgException("width and height both zero");}
		$old_width = $this->width();
		$old_heigth = $this->height();
		if (empty($width)){
			$width = floor($old_width * $height /$old_heigth);
		}else if (empty($height)){
			$height = floor($old_heigth * $width / $old_width);
		}
		
		if ($old_width == $width && $old_heigth == $height){
			return;
		}
		$me = __CLASS__;
		$dest = new $me ();
		$dest->create($width, $height);
		imagecopyresampled(
			$dest->im,
			$this->im,
			0,
			0,
			0,
			0,
			$width,
			$height,
			$old_width,
			$old_heigth
		);

		$this->destory();
		$this->im = $dest->im;
	}
	
	/*
	 * 使用扩展方式调整大小，原图比率不变
	 */
	function pad_resize($width, $height){
		$old_width = $this->width();
		$old_heigth = $this->height();
		
		//预计高度
		$pre_height = $width * $old_heigth / $old_width;
		if ($pre_height > $height){
			//太高了，以高度为准
			$pre_width = $height * $old_width / $old_heigth;
			$dx = floor(($width - $pre_width) /2);
			$x = $dx;
			$y = 0;
			
			$_x = floor($pre_width);
			$_y = $height;
		}else{
			//太矮了
			$dy = floor(($height - $pre_height)/2);
			$x = 0;
			$y = $dy;
			
			$_x = $width;
			$_y = floor($pre_height);
		}
		
		$me = __CLASS__;
		$dest = new $me ();
		$dest->create($width, $height);
		imagecopyresampled(
			$dest->im,
			$this->im,
			$x,
			$y,
			0,
			0,
			$_x,
			$_y,
			$old_width,
			$old_heigth
		);

		$this->destory();
		$this->im = $dest->im;
	}
	
	/*
	 * 原图高宽比率不变,多出来的部分剪裁掉
	 */
	function crop_resize($width, $height){
		$old_width = $this->width();
		$old_heigth = $this->height();
		
		$old_rate = $old_width * 1.0 / $old_heigth; //旧的宽高比
		$rate = $width * 1.0 / $height;
		
		if ($old_rate > $rate){
			//旧图比较宽

			//连高度都比新图大, 缩小
			//截x
			$x = floor(($old_width - $old_heigth * $rate) / 2);
			$y = 0;
			
			$_x = $old_width - $x * 2;
			$_y = $old_heigth;
				//var_dump($x, $y, $_x, $_y);

		}else{
			$x = 0;
			$y = floor(($old_heigth - $width / $rate) /2);
		
			$_x = $old_width;
			$_y = $old_heigth - $y * 2;

		}
		
		$me = __CLASS__;
		$dest = new $me ();
		$dest->create($width, $height);
		imagecopyresampled(
			$dest->im,
			$this->im,
			0,
			0,
			$x,
			$y,
			$width,
			$height,
			$_x,
			$_y
		);

		$this->destory();
		$this->im = $dest->im;
	}
	
	/*
	 * 裁剪原图
	 * int	$w	裁剪后宽度
	 * int	$h	裁剪后高度
	 * int	$x	裁剪起点x
	 * int	$y	裁剪起点y
	 */
	function crop($w, $h, $x, $y){
		$width = $this->width();
		$height = $this->height();
		
		$x = min($x, $width);
		$y = min($y, $height);
		
		$dx = min($width - $x, $w);
		$dy = min($height - $y, $h);
		
		
		$me = __CLASS__;
		$dest = new $me ();
		$dest->create($w, $h);
		imagecopyresampled(
			$dest->im,
			$this->im,
			0,
			0,
			$x,
			$y,
			$dx,
			$dy,
			$dx,
			$dy
		);

		$this->destory();
		$this->im = $dest->im;		
		
	}
	
	/*
	 * 叠加两个图
	 * img	$conver	要叠加图片
	 * int	$x		开始叠加的x坐标
	 * int	$y		开始叠加的y坐标
	 * int	$pct	透明度,默认100
	 */
	function mask($cover, $x=0, $y=0, $pct=100){
		$height = $this->height();
		$width = $this->width();
		
		$c_height = $cover->height();
		$c_width = $cover->width();
		
		$x = min($x, $width);
		$y = min($y, $height);
		$dx = min($width, $x+$c_width) - $x;
		$dy = min($height, $y + $c_height) - $y;
		
		imagecopymerge($this->im, $cover->im, $x, $y, 0, 0, $dx, $dy, $pct);
	}
	
	/*
	 * 书写文字
	 */
	function text($str, $x=5, $y=5){
		
	}
	
	/*
	 * 保存图片
	 * @param $type		保存文件类型 jpg, gif, png，为空则和原始图片一致
	 * @param $target	目标文件，若为空则直接输入
	 */
	function save($target="", $type="", $quality=0){
		if (empty($type)){
			$type = $this->source_type;
		}
		
		$map = array(
			'jpg'=>'imagejpeg',
			'gif'=>'imagegif',
			'png'=>'imagepng',
		);
		
		if (!isset($map[$type])){
			throw new imgException('Image type "{$type}" is not support, save failue');
		}
		
		if ($type == 'jpg'){
			if (empty($quality)){
				$quality = $this->defalut_quality;
			}
			return $map[$type]($this->im, $target, $quality);
		}else{
			return $map[$type]($this->im, $target);
		}
	}
	
	/*
	 * 获取某个像素字
	 */
	function getAt($x, $y){
		$color = imagecolorat($this->im, $x, $y);
		
		if (imageistruecolor($this->im)){
			$arrColor = imagecolorsforindex($this->im, $color);
			return self::rgb2int($arrColor);
		}else{
			return $color;
		}
	}
	
	/*
	 * 修改摸个像素
	 * @param $color is int 
	 */
	function setAt($x, $y, $color){
		$arrRgb = self::int2rgb($color);
		$col = imagecolorallocatealpha($this->im,$arrRgb['red'],$arrRgb['green'],$arrRgb['blue'],$arrRgb['alpha']);
        imagesetpixel($this->im,$x,$y,$col);
	}
	
	/*
	 * 销毁
	 */
	function destory(){
		imagedestroy($this->im);
        unset($this->im);
	}
	
	/*
	 * 将16进制颜色表示转为rgb数组
	 */
	public static function hex2rgb($hexColor="000000") {
		$arrColor['red'] = hexdec(substr($hexColor,0,2));
		$arrColor['green'] = hexdec(substr($hexColor,2,2));
		$arrColor['blue'] = hexdec(substr($hexColor,4,2));
		$arrColor['alpha'] = 0;
		return $arrColor;
	}
	
	/*
	 * 将16进制转int
	 */
	public static function hex2int($hexColor="000000"){
		return (hexdec(substr($hexColor,0,2)) << 16) | (hexdec(substr($hexColor,2,2)) << 8) | hexdec(substr($hexColor,4,2));
	}
	
	/*
	 * 将rgb转为16进制
	 */
	public static function rgb2hex($arrColor=array('red'=>0,'green'=>0, 'blue'=>0, 'alpha'=>0)){
		return sprintf('%x%x%x', $arrColor['red'], $arrColor['green'], $arrColor['blue']);
	}
	
	/*
	 * 将rgb数组转为int
	 */
	public static function rgb2int($arrColor=array('red'=>0,'green'=>0, 'blue'=>0, 'alpha'=>0)){
		$intColor = 
		(($arrColor['alpha'] & 0xFF) <<24)
		| (($arrColor['red'] & 0xFF) <<16)
		| (($arrColor['green'] & 0xFF) <<8)
		| (($arrColor['blue'] & 0xFF) <<0);
		
		return $intColor;	
	}
	
	/*
	 * 将int转为rgb
	 */
	public static function int2rgb($intColor=0){
		$arrColor['alpha']  = ($intColor >> 24) & 0xFF;
		$arrColor['red']    = ($intColor >> 16) & 0xFF;
		$arrColor['green']  = ($intColor >> 8) & 0xFF;
		$arrColor['blue']   = ($intColor) & 0xFF;
            
		return $arrColor;		
	}
	
	/*
	 * 将int转hex
	 */
	public static function int2hex($intColor=0){
		return sprintf('%x%x%x', ($intColor >> 16) & 0xFF, ($intColor >> 8) & 0xFF, ($intColor) & 0xFF);
	}
	
	
	/*
	 * gd info
	 */
	function gdInfo(){
		$info = gd_info();
        preg_match('/\d+/', $info['GD Version'], $match);
        $info['gd_version'] = $match[0];
        
		return $info;
	}
	
	function header() {
		switch ($this->source_type) {
			case 'gif': header("Content-type: image/gif"); return true; break;
			case 'png': header("Content-type: image/png"); return true; break;
			case 'jpg': header("Content-type: image/jpeg"); return true; break;
			default: return false;
		}
	}	
	
	/*
	 * 从文件打开一个图片
	 */
	function open($path){
		$image_data = getimagesize($path);
		if (!$image_data){
			throw new imgException("{$path} is not a image");
		}

		$this->source = $path;

		switch ($image_data[2]) { // Element 2 refers to the image type
			case IMAGETYPE_GIF:
				$this->im = imagecreatefromgif($path);
				$this->source_type = 'gif';
			    break;
			case IMAGETYPE_PNG:
				$this->im = imagecreatefrompng($path);
				$this->source_type = 'png';
			    break;
			case IMAGETYPE_JPEG:
				$this->im = imagecreatefromjpeg($path);;
				$this->source_type = 'jpg';
				break;
			default:
			    throw new imgException("{$image_data['mime']} is not support for {$path}");
		}
	}
	
	/*
	 * 从字串load一个图片
	 */
	function load($str){
		$this->im = imagecreatefromstring($str);
		if (!is_resource($this->im)){
			throw new imgException('the str is not a image string');
		}
		$this->source_type = $this->defalut_type;
	}
	
}

class imgException extends Exception{
	function __construct($message){
		parent::__construct($message, 1000);
	}
}

return;
//$captcha_dimensions = imagettfbbox($l[$x]['size'], $l[$x]['angle'], $l[$x]['font'], $l[$x]['text']);
//imagettftext($this->_owner->image, $ld['size'], $ld['angle'], $x_offset+$x_pos, $y_pos, $white, $ld['font'], $ld['text']);

//$a = new img('a.jpg');
$a = new img('b.jpg');
$c = new img('a.jpg');
//$c = $a->getAt(10, 20);
//var_dump(img::int2rgb($c));
//var_dump(img::rgb2hex(img::int2rgb($c)));
//exit;


$a->setAt(10, 10, 0);
for($i = 0; $i< 50; $i++){
	$a->setAt($i, $i, 100 << 24);
}
//$a->mask($c,400, 30, 10);
$a->header();
$a->crop(200, 300, 300, 600);
$a->save();
?>