<?php
namespace frontend\controllers;

use app\models\PayOrder;
use backend\models\GeocodeAutocomplete;
use common\models\Countries;
use Paybox\Pay\Models\Config;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;
use Yii;
use yii\base\InvalidParamException;
use yii\httpclient\Client;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use Paybox\Pay\Facade as Paybox;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */

    public function actionIndex()
    {
        $paybox = new Paybox();

        $paybox->merchant->id = 510685;
        $paybox->merchant->secretKey = '3bv3l24JBCKIUBxK';
        $paybox->order->id = 101;
        $paybox->order->description = 'test order';
        $paybox->order->amount = 10;
        $paybox->config->checkUrl   = "http://kupipolis.ibeacon.kz/site/check";
//        $paybox->config->resultUrl  = "http://kupipolis.ibeacon.kz/site/result";
        $paybox->config->successUrl = "http://kupipolis.ibeacon.kz/site/success";
        $paybox->config->failureUrl = "http://kupipolis.ibeacon.kz/site/failure";

        $paybox->config->requestMethod    = "GET";
        $paybox->config->successUrlMethod = "GET";
        $paybox->config->failureUrlMethod = "GET";

        if($paybox->init()) {
            header('Location:' . $paybox->redirectUrl);
            die;
        }
    }

    public function actionCheck(){
        $paybox = new Paybox();
        echo $paybox->cancel('Order was cancelled by phone');
        die;
    }

//    public function actionResult(){
//        if($_GET['pg_can_reject'] != 1){
//            $order = new PayOrder();
//            $order->order_id = $_GET['pg_payment_id'];
//            $order->result = $_GET['pg_result'];
//            $order->save(false);
//        }else{
//            $paybox = new Paybox();
//            echo $paybox->cancel('Ошибка');
//        }
//        die;
//    }

    public function actionSuccess(){
        echo "Платеж успешно произведен";
    }

    public function actionFailure(){
        echo "Платеж отменен";
    }

    public function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

//    public function actionIndex()
//    {
////        $vk = new VKApiClient();
////
////        $oauth = new VKOAuth();
////        $client_id = 6744108;
////        $redirect_uri = 'http://bliz.kz/site/api';
////        $display = VKOAuthDisplay::PAGE;
////        $scope = array(VKOAuthUserScope::WALL, VKOAuthUserScope::GROUPS);
////        $state = 'secret_state_code';
////
////        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);
////
////        $oauth = new VKOAuth();
////        $client_id = 6744108;
////        $client_secret = '2xa7ry4Qgvuen08pPjso';
////        $redirect_uri = 'http://bliz.kz/site/api';
////        $code = '2b8b9e9d57f9785771';
////
////        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
////        $access_token = $response['access_token'];
////
////        $vk = new VKApiClient();
////        $response = $vk->database()->getCities($access_token, array(//Города
////            'country_id' => 4,
////            'region_id' => 1702873,
//////            'offset' => 1000,
////            'count' => 1000,
////            'version' => 5.87,
////        ));
////
////        foreach ($response['items'] as $k => $v){
////            $model = new GeocodeAutocomplete();
////            $model->place_id = $this->generateRandomString();
////            $model->description = $v['title'];
////            $model->parent_id = 1702873;
////            $model->oblast = $v['region'];
////            $model->region = 0;
////            $model->save(false);
////        }
//
////        $response = $vk->database()->getRegions($access_token, array(
////            'country_id' => 4,
////            'count' => 1000,
////            'version' => 5.87,
////        ));
//
//        echo '<pre>'.print_r($response, true).'</pre>';
//
//        die;
////        var_dump($browser_url);die;
////        $data = \moonland\phpexcel\Excel::import('1.xlsx');
////
////        foreach ($data as $v)
////            foreach ($v as $v1) {
////                $model = new Countries();
////                $model->country_id = $v1[782668573];
////                $model->name = $v1['Абхазия'];
////                $model->type = $v1[1];
////                $model->save();
////            }
////
////        echo '<pre>'.print_r($data, true).'</pre>';die;
//
//        $client = new Client();
//
//        $json = '{
//"Shops": [
//{
//"Name": "Магазин  Сейфуллина"
//}
//],
//"Goods groups": [
//
//],
//"Goods": [
//{
//"Name": "Jeu`Demeure Rose Hand Cream",
//"Code": "OFC00000055",
//"Group code": "РТ000004075",
//"Price": 0
//}
//]
//}';
//$json = '';
////        $username = 'myv1';
////        $password = 'qqqq';
//        $response = $client->createRequest()
//            ->setMethod('POST')
//            ->setUrl('http://asian-cosmetics/api')
////            ->setData(["username" => $username,'password' => $password, 'json' => $json])
//            ->setData(['json' => $json])
//            ->send();
//        echo '<pre>'. print_r($response, true). '</pre>';die;
//        return $this->render('index');
//    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
