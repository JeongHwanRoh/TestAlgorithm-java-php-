package structures.BTree;

import java.util.*;

public class BTree {
    private BTreeNode root;
    private int t;

    public BTree(int t) {
        this.t = t;
        this.root = new BTreeNode(true);
    }

    public void insert(String key, String keyString, String value) {
        BTreeEntry entry = new BTreeEntry(key, keyString, value);
        BTreeNode r = root;
        if (r.keys.size() == 2 * t - 1) {
            BTreeNode s = new BTreeNode(false);
            s.children.add(r);
            splitChild(s, 0, r);
            root = s;
            insertNonFull(s, entry);
        } else {
            insertNonFull(r, entry);
        }
    }

    private void insertNonFull(BTreeNode node, BTreeEntry entry) {
        int i = node.keys.size() - 1;
        if (node.leaf) {
            node.keys.add(null); // 공간 확보
            while (i >= 0 && entry.key.compareTo(node.keys.get(i).key) < 0) {
                node.keys.set(i + 1, node.keys.get(i));
                i--;
            }
            node.keys.set(i + 1, entry);
        } else {
            while (i >= 0 && entry.key.compareTo(node.keys.get(i).key) < 0) {
                i--;
            }
            i++;
            if (node.children.get(i).keys.size() == 2 * t - 1) {
                splitChild(node, i, node.children.get(i));
                if (entry.key.compareTo(node.keys.get(i).key) > 0) {
                    i++;
                }
            }
            insertNonFull(node.children.get(i), entry);
        }
    }

    private void splitChild(BTreeNode parent, int i, BTreeNode y) {
        BTreeNode z = new BTreeNode(y.leaf);

        // Split keys
        z.keys.addAll(y.keys.subList(t, y.keys.size()));
        y.keys.subList(t, y.keys.size()).clear();

        // Split children if not a leaf
        if (!y.leaf) {
            z.children.addAll(y.children.subList(t, y.children.size()));
            y.children.subList(t, y.children.size()).clear();
        }

        // Add new child and key to parent
        parent.children.add(i + 1, z);
        parent.keys.add(i, y.keys.remove(t - 1));
    }

    public BTreeEntry search(String key) {
        return searchNode(root, key);
    }

    private BTreeEntry searchNode(BTreeNode node, String key) {
        int i = 0;
        while (i < node.keys.size() && key.compareTo(node.keys.get(i).key) > 0) {
            i++;
        }
        if (i < node.keys.size() && node.keys.get(i).key.equals(key)) {
            return node.keys.get(i);
        }
        if (node.leaf) {
            return null;
        }
        return searchNode(node.children.get(i), key);
    }
}