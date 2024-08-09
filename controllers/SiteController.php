<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Book;
use app\models\Author;
use app\models\AuthorBook;
use app\models\Subscribe;
use yii\web\UploadedFile;
use yii\base\UserException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'books', 'authors', 'edit', 'delete', 'subscribe', 'add-book', 'add-author', 'delete-books','delete-authors'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['books', 'authors'],
                        'allow' => true,
                        'roles' => ['guest', 'user'],
                    ],
                    [
                        'actions' => ['edit', 'delete', 'add-book', 'add-author','delete-books','delete-authors'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['subscribe'],
                        'allow' => true,
                        'roles' => ['guest'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionBooks() {
        return $this->render('books', [
            'books' => Book::find()->all(),
        ]);
    }

    public function actionAuthors() {
        return $this->render('authors', [
            'authors' => Author::find()->all(),
        ]);
    }

    public function actionSubscribe() {
        if (Yii::$app->user->can('subscribeAuthor')) {
            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $model = Subscribe::findOne(['user_id' => Yii::$app->user->getId(), 'author_id' => $post['author_id']]);
                if ($model) {
                    $model->delete();
                } else {
                    $model = new Subscribe();
                    $model->author_id = $post['author_id'];
                    $model->user_id = Yii::$app->user->getId();
                    if ($model->validate()) {
                        $model->save();
                    }
                }
            }
            return Yii::$app->response->redirect(['/authors']);
        } else {
            throw new UserException("У вас нет разрешения на подписку.");
        }
        
    }

    public function actionAddBook() {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $book = new Book();

            $image = UploadedFile::getInstance($book, 'photo');
            if ($image) {
                $book->photo = uniqid().'.'.$image->extension;
                if (!is_dir($book->bookPhotoPath)) {
                    mkdir($book->bookPhotoPath, 0777);
                }
                $image->fullPath = $book->bookPhotoPath.$book->photo;
            }
            
            if ($book->load($post) && $book->validate()) {

                $transaction = $book->getDb()->beginTransaction();
                
                if (false !== $book->save()) {

                    if (!$image->saveAs($image->fullPath)) {
                        @unlink($book->bookPhotoPath.$book->photo);
                        $transaction->rollBack();
                        throw new UserException("Не удалось загрузить картинку обложки книги.");
                    }

                    foreach($book->author_ids as $author_id) {
                        $authorBook = new AuthorBook();
                        $authorBook->book_id = $book->id;
                        $authorBook->author_id = $author_id;

                        if (false !== $authorBook->validate()) {

                            if (false !== $authorBook->save()) {

                                $authorBook->author->notifySubscribers($book);

                            } else {
                                @unlink($book->bookPhotoPath.$book->photo);
                                $transaction->rollBack();
                                throw new UserException("Не удалось сохранить автора у книги.");
                            }

                        } else {
                            @unlink($book->bookPhotoPath.$book->photo);
                            $transaction->rollBack();
                            throw new UserException("Данные автора и книги не прошли проверку.");
                        }
                    }

                } else {
                    $transaction->rollBack();
                    throw new UserException("Не удалось сохранить книгу.");
                }

                $transaction->commit();

                return Yii::$app->response->redirect(['/books']);
            } else {
                throw new UserException("Данные для сохранения книги не прошли проверку.");
            }
        }
        return $this->render('addBook', [
            'authors' => Author::find()->all(),
            'bookModel' => new Book(),
        ]);
    }

    public function actionDeleteBooks() {

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $bookIds = $post['book_ids'];

            if (!empty($bookIds)) {

                foreach($bookIds as $bookId => $data) {

                    $book = Book::findOne(['id' => $bookId]);

                    if ($book->photo) {
                        @unlink($book->bookPhotoPath.$book->photo);
                    }
                    
                    if (!$book->delete()) {
                        throw new UserException("Не удалось удалить книгу.");
                    }
                }

            }
            
        }
        return Yii::$app->response->redirect(['/books']);
    }

    public function actionDeleteAuthors() {

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $authorIds = $post['author_ids'];

            if (!empty($authorIds)) {

                foreach($authorIds as $authorId => $data) {

                    $author = Author::findOne(['id' => $authorId]);
                    if ($author) {
                        if ($author->authorBooks) {
                            throw new UserException("Сначала удалите книги, в которых участвовал автор!");
                        }
                        if (!$author->delete()) {
                            throw new UserException("Автора не удалось удалить.");
                        }
                    }
                }
            }
            
        }
        return Yii::$app->response->redirect(['/authors']);
    }

    public function actionAddAuthor() {

        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();
            
            $author = new Author();
            
            if ($author->load($post) && $author->validate()) {

                $author->first_name = $post['Author']['first_name'];
                $author->second_name = $post['Author']['second_name'];
                $author->middle_name = $post['Author']['middle_name'];

                if (!$author->save()) {
                    throw new UserException("Автора не удалось добавить.");
                }
                return Yii::$app->response->redirect(['/authors']);
            } else {
                throw new UserException("Данные автора не прошли проверку.");
            }
        }
        
        return $this->render('addAuthor', [
            'authorModel' => new Author(),
        ]);
    }

}
