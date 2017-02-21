<?php
    class Task
    {
        private $description;
        private $id;
        private $category_id;
        private $due_date;

        function __construct($description, $id = null, $category_id, $due_date)
        {
            $this->description = $description;
            $this->id = $id;
            $this->category_id = $category_id;
            $this->due_date = $due_date;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function getCategoryId()
        {
            return $this->category_id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO tasks (description, category_id, due_date) VALUES ('{$this->getDescription()}', {$this->getCategoryId()}, '{$this->getDueDate()}') ");
            $this->id = $GLOBALS['DB']->lastInsertId();
            // may need to add ; outside of closing curly bracket after "get Category"
        }

        function sortTask()
        {
            $GLOBALS['DB']->exec("SELECT due_date FROM tasks ORDER BY due_date DESC;");
        }

        static function getAll()
        {
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
            $tasks = array();
            foreach($returned_tasks as $task) {
                $description = $task['description'];
                $id = $task['id'];
                $category_id = $task['category_id'];
                $due_date = $task['due_date'];
                $new_task = new Task($description, $id, $category_id, $due_date);
                array_push($tasks, $new_task);
            }
            return $tasks;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks;");
        }

        function getId()
        {
            return $this->id;
        }

        static function find($search_id)
        {
            $found_task = null;
            $tasks = Task::getAll();
            foreach($tasks as $task){
                $task_id = $task->getId();
                if($task_id == $search_id) {
                    $found_task = $task;
                }
            }
            return $found_task;
        }
    }
?>
