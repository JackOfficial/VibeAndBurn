<?php
/////////first work
class Node {
    public $data;
    public $next;

    public function __construct($data) {
        $this->data = $data;
        $this->next = null;
    }
}

////////////

class LinkedList {
    private $head;

    public function __construct() {
        $this->head = null;
    }

    // Method to add a node at the end of the linked list
    public function append($data) {
        $newNode = new Node($data);
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

    // Method to display the linked list
    public function display() {
        $current = $this->head;
        while ($current !== null) {
            echo $current->data . "<br />";
            $current = $current->next;
        }
    }
}

/////////////////

// Include the Node and LinkedList classes here

// Create the linked list
$linkedList = new LinkedList();

// Replace these with your actual registration numbers
$registrationNumbers = ["27080/2024", "27081/2024", "27082/2024", "27083/2024", "27084/2024"];

// Append each registration number to the linked list
foreach ($registrationNumbers as $number) {
    $linkedList->append($number);
}

// Display the linked list
echo "Registration Numbers in Linked List: <br />";
$linkedList->display();
?>

<?php ///////////////////// second work ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Binary Tree Operations</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 20px;
    }
    .tree-info {
      margin: 10px;
    }
    button {
      margin: 5px;
      padding: 10px;
      font-size: 16px;
      cursor: pointer;
    }
    #output {
      margin-top: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>Binary Tree Operations</h2>

  <div class="tree-info">
    <button onclick="showAllNodes()">Show All Nodes</button>
    <button onclick="showLevels()">Show Possible Levels</button>
    <button onclick="showNodeCount()">Show Number of Nodes</button>
    <button onclick="showRightmostPath()">Show Path to Rightmost Leaf</button>
  </div>

  <div id="output"></div>

  <script>
    // Define the structure of a binary tree node
    class TreeNode {
      constructor(value) {
        this.value = value;
        this.left = null;
        this.right = null;
      }
    }

    // Build a simple binary tree manually
    const root = new TreeNode("27080/2024");
    root.left = new TreeNode("27081/2024");
    root.right = new TreeNode("27082/2024");
    root.left.left = new TreeNode("27083/2024");
    root.left.right = new TreeNode("27084/2024");

    // Function to return all nodes
    function showAllNodes() {
      const nodes = [];
      traverseTree(root, node => nodes.push(node.value));
      document.getElementById('output').innerText = "All Nodes: " + nodes.join(", ");
    }

    // Helper function to traverse the tree
    function traverseTree(node, callback) {
      if (node === null) return;
      callback(node);
      traverseTree(node.left, callback);
      traverseTree(node.right, callback);
    }

    // Function to calculate the number of levels
    function showLevels() {
      const levels = calculateLevels(root);
      document.getElementById('output').innerText = "Possible Levels: " + levels;
    }

    function calculateLevels(node) {
      if (node === null) return 0;
      const leftHeight = calculateLevels(node.left);
      const rightHeight = calculateLevels(node.right);
      return Math.max(leftHeight, rightHeight) + 1;
    }

    // Function to count the total number of nodes
    function showNodeCount() {
      const nodeCount = countNodes(root);
      document.getElementById('output').innerText = "Number of Nodes: " + nodeCount;
    }

    function countNodes(node) {
      if (node === null) return 0;
      return 1 + countNodes(node.left) + countNodes(node.right);
    }

    // Function to find the path from root to the rightmost leaf
    function showRightmostPath() {
      const path = [];
      let currentNode = root;
      while (currentNode) {
        path.push(currentNode.value);
        currentNode = currentNode.right; // Move to the right child
      }
      document.getElementById('output').innerText = "Path to Rightmost Leaf: " + path.join(" -> ");
    }
  </script>
</body>
</html>

