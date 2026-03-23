package structures.BTree;

public class BTreeEntry {
    String key;
    String keyString;
    String value;

    public BTreeEntry(String key, String keyString, String value) {
        this.key = key;
        this.keyString = keyString;
        this.value = value;
    }
}