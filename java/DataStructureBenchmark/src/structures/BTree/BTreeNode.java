package structures.BTree;

import java.util.ArrayList;
import java.util.List;

public class BTreeNode {
    boolean leaf;
    List<BTreeEntry> keys = new ArrayList<>();
    List<BTreeNode> children = new ArrayList<>();

    public BTreeNode(boolean leaf) {
        this.leaf = leaf;
    }
}