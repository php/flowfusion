--TEST--
Translated seed (inferredbugs-java: community/1)
--FILE--
<?php

class Question {
    public $creator;
}

class User {}

class PaginationDTO {
    public $questions;
    public $totalPage;

    public function setPagination($totalCount, $page, $size) {
        $this->totalPage = ceil($totalCount / $size);
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
    }
}

class QuestionMapper {
    public function count() {
        return 100;  // Simulating total count
    }

    public function list($offset, $size) {
        $questions = [];
        for ($i = 0; $i < $size; $i++) {
            if ($offset + $i < 100) {
                $question = new Question();
                $question->creator = rand(1, 10);  // Simulating creator ID
                $questions[] = $question;
            }
        }
        return $questions;
    }
}

class UserMapper {
    public function findById($id) {
        return new User();
    }
}

$page = 0;  // Edge case: invalid page number
$size = 10;

$paginationDTO = new PaginationDTO();
$questionMapper = new QuestionMapper();
$userMapper = new UserMapper();
$totalCount = $questionMapper->count();
$paginationDTO->setPagination($totalCount, $page, $size);

if ($page < 1) {
    $page = 1;
}

if ($page > $paginationDTO->totalPage) {
    $page = $paginationDTO->totalPage;
}

$offset = $size * ($page - 1);
$questions = $questionMapper->list($offset, $size);
$questionDTOList = [];

foreach ($questions as $question) {
    $user = $userMapper->findById($question->creator);
    $questionDTO = new stdClass();  // Using stdClass to mimic QuestionDTO
    $questionDTO->user = $user;
    $questionDTOList[] = $questionDTO;
}

$paginationDTO->setQuestions($questionDTOList);
--EXPECT--
