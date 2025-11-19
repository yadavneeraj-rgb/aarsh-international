class OrderWebSocket {
    constructor() {
        this.echo = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.init();
    }

    init() {
        try {
            // Initialize Laravel Echo
            this.echo = new Echo({
                broadcaster: "reverb",
                key: process.env.MIX_REVERB_APP_KEY || "your-reverb-key",
                wsHost: window.location.hostname,
                wsPort: process.env.MIX_REVERB_PORT || 6001,
                wssPort: process.env.MIX_REVERB_PORT || 6001,
                forceTLS: false,
                enabledTransports: ["ws", "wss"],
            });

            this.listenForOrders();
            this.setupConnectionHandlers();
        } catch (error) {
            console.error("Failed to initialize WebSocket:", error);
            this.handleConnectionError("Initialization failed");
        }
    }

    listenForOrders() {
        if (!this.echo) {
            console.error("Echo not initialized");
            return;
        }

        // Listen for new orders
        this.echo.channel("order-updates").listen(".order.updated", (data) => {
            console.log("📦 New order received via WebSocket:", data);

            if (data.action === "created") {
                // Call the function from order.blade.php
                if (window.addOrderToUI) {
                    window.addOrderToUI(data);
                }
            } else if (data.action === "updated") {
                if (window.updateOrderInUI) {
                    window.updateOrderInUI(data);
                }
            }

            // Show notification
            this.showNotification(
                `New order #${data.id} from ${data.first_name}`,
                "success"
            );
        });

        console.log("✅ Listening for order updates on channel: order-updates");
    }

    setupConnectionHandlers() {
        // Handle connection established
        this.echo.connector.socket.on("connect", () => {
            console.log("✅ WebSocket connected successfully");
            this.isConnected = true;
            this.reconnectAttempts = 0;

            if (window.updateConnectionStatus) {
                window.updateConnectionStatus(
                    "connected",
                    "Live updates active"
                );
            }

            this.showNotification("Live orders connected", "success");
        });

        // Handle connection errors
        this.echo.connector.socket.on("error", (error) => {
            console.error("❌ WebSocket error:", error);
            this.handleConnectionError("Connection error");
        });

        // Handle disconnection
        this.echo.connector.socket.on("disconnect", (reason) => {
            console.warn("🔌 WebSocket disconnected:", reason);
            this.isConnected = false;

            if (window.updateConnectionStatus) {
                window.updateConnectionStatus(
                    "disconnected",
                    "Connection lost"
                );
            }

            this.attemptReconnect();
        });
    }

    handleConnectionError(message) {
        this.isConnected = false;

        if (window.updateConnectionStatus) {
            window.updateConnectionStatus("error", message);
        }

        this.attemptReconnect();
    }

    attemptReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            const delay = Math.min(1000 * this.reconnectAttempts, 10000);

            console.log(
                `🔄 Attempting reconnect ${this.reconnectAttempts}/${this.maxReconnectAttempts} in ${delay}ms`
            );

            if (window.updateConnectionStatus) {
                window.updateConnectionStatus(
                    "connecting",
                    `Reconnecting... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`
                );
            }

            setTimeout(() => {
                if (!this.isConnected) {
                    this.init();
                }
            }, delay);
        } else {
            console.error("❌ Max reconnection attempts reached");
            if (window.updateConnectionStatus) {
                window.updateConnectionStatus("error", "Failed to connect");
            }
        }
    }

    showNotification(message, type = "info") {
        // Use the notification system from order.blade.php
        if (window.showFallbackNotification) {
            window.showFallbackNotification(message);
        } else {
            // Fallback to browser notification
            if (Notification.permission === "granted") {
                new Notification("New Order!", { body: message });
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then((permission) => {
                    if (permission === "granted") {
                        new Notification("New Order!", { body: message });
                    }
                });
            }

            // Fallback to console
            console.log(`📢 ${type.toUpperCase()}: ${message}`);
        }
    }

    disconnect() {
        if (this.echo) {
            this.echo.disconnect();
            this.isConnected = false;
            console.log("🔌 WebSocket disconnected manually");
        }
    }
}

// Initialize when document is ready
document.addEventListener("DOMContentLoaded", function () {
    window.orderWebSocketInstance = new OrderWebSocket();
});
