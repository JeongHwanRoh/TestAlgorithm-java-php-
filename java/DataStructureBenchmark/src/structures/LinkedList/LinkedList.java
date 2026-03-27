package structures.LinkedList;

import structures.common.Node;

public class LinkedList {

	private Node head;

	public void insert(String key, String keyString, String value) {
		Node node = new Node(key, keyString, value);
		node.next = head;
		head = node;
	}

	public Node search(String key) {
		Node curr = head;
		while (curr != null) {
			if (curr.key.equals(key))
				return curr;
			curr = curr.next;
		}
		return null;
	}

	public void sort() {
		head = mergeSort(head);
	}

	private Node mergeSort(Node node) {
		if (node == null || node.next == null)
			return node;

		Node middle = getMiddle(node);
		Node nextOfMiddle = middle.next;
		middle.next = null;

		Node left = mergeSort(node);
		Node right = mergeSort(nextOfMiddle);

		return sortedMerge(left, right);
	}

	private Node sortedMerge(Node a, Node b) {
		if (a == null)
			return b;
		if (b == null)
			return a;

		Node result;
		if (a.key.compareTo(b.key) <= 0) {
			result = a;
			result.next = sortedMerge(a.next, b);
		} else {
			result = b;
			result.next = sortedMerge(a, b.next);
		}
		return result;
	}

	private Node getMiddle(Node node) {
		if (node == null || node.next == null)
			return node;

		Node slow = node, fast = node.next;
		while (fast != null && fast.next != null) {
			slow = slow.next;
			fast = fast.next.next;
		}
		return slow;
	}

}