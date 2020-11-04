<?php

ini_set('log_errors','on'); ////ログを取るか
ini_set('error_log','php.log'); ////ログの出力ファイルを指定
session_start(); //セッションを使う

// 自分のHP
define('MY_HP',500);
//モンスター格納用の配列
$monsters = array();

//種類クラス(モンスター)
class MonType{//クラス定数
   const dinosaur1 = 1;
   const dinosaur2 = 2;
   const dinosaur3 = 3;
   const dinosaur4 = 4;
   const dinosaur5 = 5;
   const dinosaur6 = 6;
   const dinosaur7 = 7;
   const dinosaur8 = 8;
}
//種類クラス(勇者)
class HeroType{//クラス定数
   const Hero01 = 1;
   const Hero02 = 2;
   const Hero03 = 3;
}

// 人クラス
class Human {
   // プロパティ(オブジェクトの変数(情報))
   protected $name;//継承するという仮定でアクセス権をprotectedにしている。
   protected $type;
   protected $hp;
   protected $attackMin = '';
   protected $attackMax = '';
   protected $img;

   // コンストラクタ(関数)・・・インスタンス化されたタイミングで実行される特別なメソッド
   public function __construct($name, $type, $hp, $attackMin, $attackMax, $img){
      $this->name = $name;
      $this->type = $type;
      $this->hp = $hp;
      $this->attackMin = $attackMin;
      $this->attackMax = $attackMax;
      $this->img = $img;
   }
   //セッター
   public function setHp($str){
      $this->hp = $str;
   }

   //ゲッター
   public function getName(){
      return $this->name;
   }
   public function getHp(){
      return $this->hp;
   }
   public function getImg(){
      return $this->img;
   }
   //勇者の泣き方
   public function heroCry(){
      switch($this->type){
         case HeroType::Hero01:
            History::HeroSet('イテェ');
            break;
         case HeroType::Hero02:
            Histort::HeroSet('いて〜');
            break;
         case HeroType::Hero03:
            History::HeroSet('効かん');
      }
   }

   public function attack(){
      $attackPoint = mt_rand($this->attackMin, $this->attackMax);
      if(!mt_rand(0,9)){//10分の１の確率でロケットランチャーを発動
         $attackPoint *= 3;
         $attackPoint = (int)$attackPoint;
         History::HeroSet($this->getName().'がロケットランチャーを使った！！');
      }
      $_SESSION['monster']->setHp($_SESSION['monster']->getHp() - $attackPoint);
      History::HeroSet($this->getName().'が'.$attackPoint.'ポイントのダメージを与えた');
      History::MonSet($_SESSION['monster']->getName().'が'.$attackPoint.'ポイントのダメージを受けた');
   }
}

//クラス(設計図)の作成
class Monster{
   // プロパティ(オブジェクトの変数(情報))
   //カプセル化と継承する
   protected $name;
   protected $hp;
   protected $img;
   protected $attack = '';
   protected $montype;
   // コンストラクタ(関数)・・・インスタンス化されたタイミングで実行される特別なメソッド
   public function __construct($name, $hp, $img, $attack, $montype){
      $this->name = $name;
      $this->hp = $hp;
      $this->img = $img;
      $this->attack = $attack;
      $this->montype = $montype;
   }

   // メソッド(オブジェクトの関数（処理）) モンスターが勇者に攻撃する
   public function attack(){
      $attackPoint = $this->attack;
      if(!mt_rand(0,9)){
         $attackPoint *= 1.5;
         $attackPoint = (int)$attackPoint;
         History::MonSet($this->name.'のクリティカルヒット');
      }
      $_SESSION['myhp'] -= $attackPoint;
      History::MonSet($this->name.'が'.$attackPoint.'ポイントのダメージを与えた');
      History::HeroSet($_SESSION['human']->getName().'が'.$attackPoint.'ポイントのダメージを受けた');
   }

   // セッター➡︎プロパティに値を設定するメソッド
   public function setHp($num){
      $this->hp = filter_var($num,FILTER_VALIDATE_INT);
   }
   public function setAtttack($num){
      $this->attack = (int)filter_var($num,FILTER_VALIDATE_FLOAT);
   }

   // ゲッター➡︎プロパティの値を戻り値として返すメソッド
   public function getName(){
      return $this->name;
   }
   public function getHp(){
      return $this->hp;
   }
   public function getImg(){
      if(empty($this->img)){
         return 'img/no-img.png';
      }
      return $this->img;
   }
   public function getAttack(){
      return $this->attack;
   }
   //モンスターの泣き方
   public function monCry(){
      switch($this->montype){
         case MonType::dinosaur1:
            History::MonSet('クークー');
            break;
         case MonType::dinosaur2:
            Histort::MonSet('グー');
            break;
         case MonType::dinosaur3:
            History::MonSet('ガフッ');
            break;
         case MonType::dinosaur4:
            History::MonSet('ガーガー');
            break;
         case MonType::dinosaur5:
            History::MonSet('クッ');
            break;
         case MonType::dinosaur6:
            History::MonSet('ガルルルル');
            break;
         case MonType::dinosaur7:
            History::MonSet('フンｯ');
            break;
         case MonType::dinosaur8:
            History::MonSet('ガオー');
      }
   }
}

//継承
class HaoMonster extends Monster{
   private $haoAttack;
   function __construct($name, $hp, $img, $attack, $haoAttack, $montype){
      parent::__construct($name, $hp, $img, $attack, $montype);//親のコンストラクタを使う
      $this->haoAttack = $haoAttack;
   }
   public function getHaoAttack(){
      return $this->haoAttack;
   }

   //オーバーライド(親のattackメソッドを上書き)
   public function attack(){
      if(mt_rand(0,5)){//ロケットランチャーパターン
         History::MonSet($this->name.'のロケットランチャーが発動した！！');
         //$_SESSION['mon_history'] .= $this->name.'のロケットランチャーが発動した！！<br />';
         $_SESSION['myhp'] -= $this->haoAttack;
         History::MonSet($this->name.'が'.$this->haoAttack.'ポイントのダメージを与えた');
         //$_SESSION['mon_history'] .= $this->name.'が'.$this->haoAttack.'ポイントのダメージを与えた<br>';
         History::HeroSet($_SESSION['human']->getName().'が'.$this->haoAttack.'ポイントのダメージを受けた');
         //$_SESSION['hero_history'] .= 'ヒーローが'.$this->haoAttack.'ポイントのダメージを受けた<br>';
      }else{//親のメソッドを使って普通の攻撃パターン
         parent::attack();
      }
   }
}

//履歴管理クラス(静的メンバ)
class History{
   public static function MonSet($str){
      //$_SESSION['mon_history']が作られていない場合は作る
      if(empty($_SESSION['mon_history'])) $_SESSION['mon_history'] = '';
      $_SESSION['mon_history'] .= $str . '<br>';
   }
   public static function HeroSet($str){
      //$_SESSION['hero_history']が作られていない場合は作る
      if(empty($_SESSION['hero_history'])) $_SESSION['hero_history'] = '';
      $_SESSION['hero_history'] .= $str . '<br>';
   }
   public static function clear(){
      unset($_SESSION['mon_history']);
      unset($_SESSION['hero_history']);
   }
}

// インスタンス生成
$human = new Human('ヒーロー', HeroType::Hero01 ,500, 50, 150,'img/hero.jpeg' );
$monsters[] = new Monster( '恐竜1', 350, 'img/monster1.jpg', mt_rand(50,100), MonType::dinosaur1 );
$monsters[] = new HaoMonster( '恐竜2', 400, 'img/monster2.jpeg', mt_rand(50,100), mt_rand(100,150), MonType::dinosaur2 );
$monsters[] = new HaoMonster( '恐竜3', 300, 'img/monster3.jpg', mt_rand(50,80), mt_rand(100,150), MonType::dinosaur3 );
$monsters[] = new Monster( '恐竜4', 300, 'img/monster4.jpeg', mt_rand(40,90), MonType::dinosaur4 );
$monsters[] = new Monster( '恐竜5', 300, 'img/monster5.jpg', mt_rand(40,80), MonType::dinosaur5 );
$monsters[] = new Monster( '恐竜6', 100, 'img/monster6.png', mt_rand(10,50), MonType::dinosaur6 );
$monsters[] = new Monster( '恐竜7', 150, 'img/monster7.jpg', mt_rand(30,70), MonType::dinosaur7 );
$monsters[] = new Monster( '恐竜8', 130, 'img/monster8.jpg', mt_rand(20,60), MonType::dinosaur8 );

error_log(print_r($monsters,true));

// モンスター生成関数
function createMonster(){
   global $monsters;
   $monster = $monsters[mt_rand(0,7)];//ランダムにインスタンスを呼び出す
   History::MonSet($monster->getName().'が現れた');
   //$_SESSION['mon_history'] .= $monster->getName().'が現れた<br>';
   $_SESSION['monster'] = $monster;
}
//ヒーローの生成
function createHuman(){
   global $human;
   $_SESSION['human'] =  $human;
}

// error_log(print_r(createMonster(),true));

// 初期化用関数
function init(){
   History::clear();
   $_SESSION['knockDownCount'] = 0;
   $_SESSION['myhp'] = MY_HP;
   createMonster();
   createHuman();
}
function gameOver(){
   $_SESSION = array();
}

//1.post送信されていた場合
if(!empty($_POST)){
   error_log(print_r($_POST,true));

   $attackFlg = (!empty($_POST['attack'])) ? true : false;
   $startFlg = (!empty($_POST['start'])) ? true : false;

   if($startFlg){//ゲームスタートまたはゲームリスタートを押した場合
      init();
      //$_SESSION['mon_history'] = 'ゲームスタート<br>';
      History::HeroSet('恐竜だー');
      //$_SESSION['hero_history'] = '恐竜だ〜〜〜<br>';

   }else{
      // 攻撃するを押した場合
      if($attackFlg){
         // ランダムでモンスターに攻撃を与える
         $_SESSION['human']->attack();
         //モンスターが叫ぶ
         $_SESSION['monster']->monCry();

         //モンスターから攻撃を受ける
         //オーバーライドしてるので、ただ攻撃するように指示するだけで済む。前回までのコード「みたいに進行役がどっちの攻撃するか判定しなくて良いです。
         $_SESSION['monster']->attack();
         //ヒーローが叫ぶ
         $_SESSION['human']->heroCry();

         //モンスターが死ぬか、勇者が死ぬかの判定
         if($_SESSION['myhp'] <= 0){
            gameOver();
         }elseif($_SESSION['monster']->getHp() <= 0){
            History::HeroSet($_SESSION['human']->getName().'が'.$_SESSION['monster']->getName().'を倒した');
            //$_SESSION['hero_history'] .= 'ヒーローが'.$_SESSION['monster']->getName().'を倒した<br>';
            History::MonSet($_SESSION['monster']->getName().'が倒れた');
            //$_SESSION['mon_history'] .= $_SESSION['monster']->getName().'が倒れた<br>';
            createMonster();
            $_SESSION['knockDownCount'] = $_SESSION['knockDownCount'] + 1;
         }

      }else{
         History::clear();
         History::HeroSet('全員逃げることだけ考えろ!!<br>今の俺たちじゃこいつには勝てね!!');
         //$_SESSION['hero_history'] .= '全員逃げることだけ考えろ!!<br>今の俺たちじゃこいつには勝てね!!<br>';
         createMonster();
      }

   }
   $_POST = array();
}


?>

<!DOCTYPE html>
<html lang="ja">
   <head>
      <meta charset="UTF-8">
      <title>ジュラシックサバイバル</title>
      <link rel="stylesheet" type="text/css" href="style.css">
   </head>
   <body>
      <?php if(empty($_SESSION)) { ?>
         <div class="start_screen">
            <form action="" method="post" class="start-form">
               <img src="./img/sample4.jpg" alt="" >
               <input type="submit" name="start" value="ゲームスタート">
            </form>
         </div>
      <?php }else{ ?>
         <div class="main">
             <div class="main-top">
                <p style="overflow: scroll;"><?php echo (!empty($_SESSION['mon_history'])) ? $_SESSION['mon_history'] : ''; ?></p>
                <section class="top-wrap">
                   <h2><?php echo $_SESSION['monster']->getName().'が現れた'; ?></h2>
                   <img src="<?php echo $_SESSION['monster']->getImg(); ?>" alt="">
                   <p><?php echo $_SESSION['monster']->getName(); ?>の残りHP：<?php echo $_SESSION['monster']->getHp(); ?></p>
                </section>
             </div>
             <div class="main-bottom">
                <section class="left-wrap">
                   <img src="<?php echo $_SESSION['human']->getImg(); ?>" alt="">
                   <p><?php echo $_SESSION['human']->getName(); ?>の残りHP：<?php echo $_SESSION['myhp']; ?></p>
                   <p>倒した敵の数：<?php echo $_SESSION['knockDownCount']; ?></p>
                   <form action="" method="post">
                      <input type="submit" name="attack" value="攻撃">
                      <input type="submit" name="escape" value="逃げる">
                      <input type="submit" name="start" value="ゲームリスタート">
                   </form>
                </section>
                <section class="right-wrap">
                   <p style="overflow: scroll;"><?php echo (!empty($_SESSION['hero_history'])) ? $_SESSION['hero_history'] : ''; ?></p>
                </section>
             </div>
         </div>
      <?php } ?>
    </body>
 </html>
