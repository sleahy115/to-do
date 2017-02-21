<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost:8889;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
     $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {

        return $app['twig']->render('tasks.html.twig', array('categories' => Category::getAll()));
    });

    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('tasks.html.twig', array('categories'=>Category::getAll(), 'tasks'=>Task::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app){
      $category = Category::find($id);
      return $app ['twig']->render('category.html.twig', array('category'=> $category, 'tasks'=>$category->getTasks()));
    });

    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $due_date = $_POST['due_date'];
        $task = new Task($description, $id = null, $category_id, $due_date);
        $task->save();
        $category = Category::find($category_id);
        // $task->sortTask();
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks'=>$category->getTasks()));
    });

    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        Category::deleteAll();
        return $app['twig']->render('delete_tasks.html.twig');
    });


    return $app;
?>
