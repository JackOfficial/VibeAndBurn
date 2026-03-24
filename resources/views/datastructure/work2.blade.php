<?php
// Define a node in the linked list
class ListNode {
    public $value;
    public $next;

    public function __construct($value) {
        $this->value = $value;
        $this->next = null;
    }
}

// Define a LinkedList class with methods to insert and display
class LinkedList {
    private $head;

    public function __construct() {
        $this->head = null;
    }

    public function insert($value) {
        $newNode = new ListNode($value);
        if ($this->head === null) {
            $this->head = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $newNode;
        }
    }

    public function display() {
        $elements = [];
        $current = $this->head;
        while ($current !== null) {
            $elements[] = $current->value;
            $current = $current->next;
        }
        return $elements;
    }
}

// Example registration numbers (Set A)
$A = ["27080", "25616", "25458", "25456", "25453"];

// Create linked lists for even-indexed and odd-indexed elements
$evenList = new LinkedList();
$oddList = new LinkedList();

// Separate registration numbers based on index % 2
foreach ($A as $index => $regNumber) {
    if ($index % 2 === 0) {
        $evenList->insert($regNumber);
    } else {
        $oddList->insert($regNumber);
    }
}

// Output the results
echo "<h3>Store data of set A in memory using a linked list based on the index modulo of 2:</h3>";
echo "<b>Even-indexed linked list:</b> " . implode(", ", $evenList->display()) . "<br />";
echo "<b>Odd-indexed linked list:</b> " . implode(", ", $oddList->display()) . "<br />";
?>
