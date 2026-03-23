package structures.Arraylist;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

import structures.common.Items;

public class Arraylist {

	private final List<Items> items = new ArrayList<>();

	public void insert(String key, String keyString, String value) {
		items.add(new Items(key, keyString, value));
	}

	public Items search(String key) {
		for (Items item : items) {
			if (item.key.equals(key)) {
				return item;
			}
		}
		return null;
	}

	public void sortByKeyString() {
		items.sort(Comparator.comparing(i -> i.keyString));
	}
	
	public void sort() {
		items.sort(Comparator.comparing(i -> i.key));
	}

	public int count() {
		return items.size();
	}
}