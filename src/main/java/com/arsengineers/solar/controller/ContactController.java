package com.arsengineers.solar.controller;

import com.arsengineers.solar.model.ContactMessage;
import com.arsengineers.solar.repository.ContactMessageRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api")
public class ContactController {

    @Autowired
    private ContactMessageRepository repository;

    @PostMapping("/contact")
    public ResponseEntity<?> submitContact(@RequestBody ContactMessage message) {
        try {
            if (message.getName() == null || message.getEmail() == null || message.getPhone() == null
                    || message.getMessage() == null) {
                return ResponseEntity.badRequest().body(Map.of("success", false, "message", "Missing required fields"));
            }

            repository.save(message);
            return ResponseEntity.ok(
                    Map.of("success", true, "message", "Thank you for your message! We will get back to you soon."));
        } catch (Exception e) {
            e.printStackTrace();
            return ResponseEntity.internalServerError()
                    .body(Map.of("success", false, "message", "Error saving message"));
        }
    }

    @GetMapping("/messages")
    public List<ContactMessage> getMessages() {
        return repository.findAllByOrderByCreatedAtDesc();
    }

    @PostMapping("/messages/{id}/read")
    public ResponseEntity<?> markAsRead(@PathVariable Long id) {
        return repository.findById(id).map(msg -> {
            msg.setRead(true);
            repository.save(msg);
            return ResponseEntity.ok(Map.of("success", true));
        }).orElse(ResponseEntity.notFound().build());
    }

    @DeleteMapping("/messages/{id}")
    public ResponseEntity<?> deleteMessage(@PathVariable Long id) {
        return repository.findById(id).map(msg -> {
            repository.delete(msg);
            return ResponseEntity.ok(Map.of("success", true));
        }).orElse(ResponseEntity.notFound().build());
    }
}
