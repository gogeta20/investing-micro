package mvm.micro.core.application.usecase.GenerateReport;

import org.springframework.stereotype.Service;
import java.util.concurrent.CompletableFuture;
import java.io.*;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.net.URI;
import java.util.zip.ZipOutputStream;
import java.util.zip.ZipEntry;

@Service
public class GenerateReport {
    private final HttpClient httpClient;
    private static final String POKEMON_API = "https://pokeapi.co/api/v2/ability/1";

    public GenerateReport() {
        this.httpClient = HttpClient.newHttpClient();
    }

    public void process(GenerateReportCommand command){
        try {
            this.execute()
                .thenAccept(filePath -> {
                    System.out.println("Report generated at: " + filePath);
                })
                .exceptionally(throwable -> {
                    System.err.println("Error: " + throwable.getMessage());
                    return null;
                });
        } catch (Exception e) {
            throw new RuntimeException("Error in create report: " + e.getMessage());
        }
    }

    public CompletableFuture<String> execute() {
        return CompletableFuture.supplyAsync(() -> {
            try {
                // Especificar un directorio personalizado
                String customDir = "/app/code/files/";
                File zipFile = new File(customDir + "pokemon_report_" + System.currentTimeMillis() + ".zip");

                try (ZipOutputStream zipOut = new ZipOutputStream(new FileOutputStream(zipFile))) {
                    // Obtener lista de pokemons y crear CSV
                    String pokemonList = fetchPokemonList();

                    // Crear entrada ZIP para el CSV
                    ZipEntry zipEntry = new ZipEntry("pokemon_list.csv");
                    zipOut.putNextEntry(zipEntry);

                    // Escribir datos al ZIP
                    zipOut.write(pokemonList.getBytes());
                    zipOut.closeEntry();
                }

                return zipFile.getAbsolutePath();
            } catch (Exception e) {
                throw new RuntimeException("Error generating report: " + e.getMessage());
            }
        });
    }

    private String fetchPokemonList() throws Exception {
        HttpRequest request = HttpRequest.newBuilder()
            .uri(URI.create(POKEMON_API + "?limit=1000"))
            .GET()
            .build();

        HttpResponse<String> response = httpClient.send(request,
            HttpResponse.BodyHandlers.ofString());

        // Aquí procesarías el JSON y lo convertirías a CSV
        return response.body();
    }
}
