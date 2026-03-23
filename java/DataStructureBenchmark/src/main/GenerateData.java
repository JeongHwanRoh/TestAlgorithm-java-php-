package main;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.Random;
import java.util.UUID;

public class GenerateData {
    public static void main(String[] args) throws IOException {
        String[] animals = {"tiger", "lion", "wolf", "bear", "eagle", "shark", "fox", "whale", "dragon", "panther"};
        String[] natures = {"river", "forest", "ocean", "mountain", "desert", "sky", "storm", "wind", "fire", "ice"};
        String[] concepts = {"energy", "power", "force", "light", "shadow", "speed", "time", "gravity", "signal", "pulse"};
        String[] cosmos = {"galaxy", "star", "planet", "universe", "orbit", "comet", "nebula", "meteor", "cosmos", "void"};

        int target = 200_000;
        Random rand = new Random();

        // Ensure the 'data' directory exists
        File dataDir = new File("data");
        if (!dataDir.exists()) {
            dataDir.mkdirs();
        }

        try (FileWriter fw = new FileWriter("data/dataset.txt")) {
            fw.write("key,keyString,value\n");
            for (int i = 0; i < target; i++) {
                String uuid = UUID.randomUUID().toString();
                String a = animals[rand.nextInt(animals.length)];
                String b = natures[rand.nextInt(natures.length)];
                String c = concepts[rand.nextInt(concepts.length)];
                String d = cosmos[rand.nextInt(cosmos.length)];
                String keyString = a + "-" + b + "-" + c + "-" + d;
                String value = "https://example.com/" + a + "/" + b + "/" + c + "/" + d + "/" + Long.toHexString(rand.nextLong());
                fw.write(uuid + "," + keyString + "," + value + "\n");
                if (i % 100_000 == 0) {
                    System.out.println("Generated: " + i);
                }
            }
        }
        System.out.println("총 " + target + "개 생성 완료");
    }
}