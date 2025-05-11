// Listen for notifications
document.addEventListener('DOMContentLoaded', () => {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    
    if (userId && window.Echo) {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                showNotification(notification);
            });
    }
});

function showNotification(notification) {
    const { title, message, type } = notification;
    
    // You can customize this to use your preferred notification library
    // This is a basic example using native browser notifications
    if ("Notification" in window) {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                new Notification(title, {
                    body: message,
                    icon: '/path/to/your/icon.png' // Add your notification icon path
                });
            }
        });
    }
    
    // You can also show an in-app notification
    // Example: Create a div element for the notification
    const notificationElement = document.createElement('div');
    notificationElement.className = `notification notification-${type}`;
    notificationElement.innerHTML = `
        <h4>${title}</h4>
        <p>${message}</p>
    `;
    
    document.body.appendChild(notificationElement);
    
    // Remove the notification after 5 seconds
    setTimeout(() => {
        notificationElement.remove();
    }, 5000);
}