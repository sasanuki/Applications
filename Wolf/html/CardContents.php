<?php

class CardContents
{
    public $title = '';
    public $recommend = '';
    public $landingPage = '';
    public $endDay = '';

    public function get_current_contents(){

        // DBから今月のリストを取得する

        $this->title = 'タイトル';
        $this->recommend = 'おすすめポイントの解説';
        $this->landingPage = '#';
        $this->endDay = date('Y / m / d');
    }
}