package structures.common;

public class Node {
	public String key;
	public String keyString;
	public String value;
	public Node next;
	
	public Node(String key, String KeyString, String value) {
		this.key = key;
		this.keyString =keyString;
		this.value = value;
	}
}
