<?php
/**
 * Classe que realiza toda interação com o facebook
 * @author Alexsandro Souza
 */
class FacebookApp extends Facebook
{

	public function __construct()
	{
		Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
		parent::__construct(array(
			'appId' => FACE_APP_ID,
			'secret' => FACE_APP_SECRET,
		));
	}
	
	public function api($uri, $action, $params = null){
		try {
			return parent::api($uri, $action, $params); //POSTANDO FOTO NO ALBUM
		} catch (FacebookApiException $e) {
			echo $e->getMessage();
			return false;
		}
	}
	    
	public function getFeed($groupId, $until = null){
        $until = $until ? '&'. $until : '';
        $query = $groupId . '/feed?fields=message%2Cfrom%2Ccomments.limit(100).fields(from%2Cmessage)&limit=50'. $until;
		$feed=  $this->api($query, 'get');
		if(isset($feed['data'])){
			return $feed;
		}
		return false;
	}
    
}

?>
