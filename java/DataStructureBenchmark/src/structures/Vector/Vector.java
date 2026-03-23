package structures.Vector;

import java.util.ArrayList;
import java.util.Comparator;

import structures.common.Items;

public class Vector {
	private final ArrayList<Items> vector = new ArrayList<>();

	public void insert(String key, String keyString, String value) {
		vector.add(new Items(key, keyString, value));
	}

	public Items search(String key) {
		for (Items item : vector) {
			if (item.key.equals(key))
				return item;
		}
		return null;
	}

	public void sortByKeyString() {
		vector.sort(Comparator.comparing(i -> i.keyString));
	}

	public void sort() {
		vector.sort(Comparator.comparing(i -> i.key));
	}
}