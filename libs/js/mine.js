// #By Amar Syahril HMC #
// popup star

class Popup {
  static create(config) {
    const backdrop = document.createElement("div");
    backdrop.className = "popup-backdrop";

    const container = document.createElement("div");
    container.className = "popup-container";

    // Icon
    const icon = document.createElement("div");
    icon.className = `popup-icon ${config.type}`;
    icon.innerHTML = this.getIcon(config.type);

    // Title
    const title = document.createElement("div");
    title.className = "popup-title";
    title.textContent = config.title;

    // Message
    const message = document.createElement("div");
    message.className = "popup-message";
    message.textContent = config.message;

    // Buttons
    const buttons = document.createElement("div");
    buttons.className = "popup-buttons";

    config.buttons.forEach((btn) => {
      const button = document.createElement("button");
      button.className = `popup-button ${btn.type}-bg`;
      button.textContent = btn.text;
      button.onclick = () => {
        btn.action();
        this.close(backdrop);
      };
      buttons.appendChild(button);
    });

    // Gabungkan semua elemen
    container.appendChild(icon);
    container.appendChild(title);
    container.appendChild(message);
    if (config.buttons.length > 0) container.appendChild(buttons);
    backdrop.appendChild(container);
    document.body.appendChild(backdrop);

    // Tampilkan popup
    setTimeout(() => backdrop.classList.add("active"), 10);

    // Auto close
    if (config.duration) {
      setTimeout(() => this.close(backdrop), config.duration);
    }

    // Close saat klik di luar
    backdrop.addEventListener("click", (e) => {
      if (e.target === backdrop) this.close(backdrop);
    });

    // Close saat tekan ESC
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") this.close(backdrop);
    });
  }

  static close(backdrop) {
    backdrop.classList.remove("active");
    setTimeout(() => backdrop.remove(), 300);
  }

  static getIcon(type) {
    const icons = {
      success: "✓",
      error: "⚠",
      warning: "!",
      info: "i",
      read: "📖",
    };
    return icons[type] || "";
  }

  static success(title, message, duration) {
    this.create({
      type: "success",
      title,
      message,
      duration,
      buttons: [
        {
          text: "OK",
          type: "success",
          action: () => console.log("Success action"),
        },
      ],
    });
  }
  static warning2(title, message, duration) {
    this.create({
      type: "warning",
      title,
      message,
      duration,
      buttons: [
        {
          text: "OK",
          type: "success",
          action: () => console.log("Success action"),
        },
      ],
    });
  }

  static error(title, message, duration) {
    this.create({
      type: "error",
      title,
      message,
      duration,
      buttons: [
        {
          text: "Tutup",
          type: "error",
          action: () => console.log("Error action"),
        },
      ],
    });
  }

  static warning(title, message, duration = null, onConfirm = null) {
    this.create({
      type: "warning",
      title,
      message,
      duration,
      buttons: [
        {
          text: "Batal",
          type: "warning",
          action: () => console.log("Cancel action"),
        },
        {
          text: "Lanjut",
          type: "warning",
          action: () => {
            console.log("Continue action");
            if (onConfirm) onConfirm(); // Jalankan fungsi callback jika ada
          },
        },
      ],
    });
  }

  static approve(title, message, duration = null, onConfirm = null) {
    this.create({
      type: "info",
      title,
      message,
      duration,
      buttons: [
        {
          text: "Batal",
          type: "error",
          action: () => console.log("Cancel action"),
        },
        {
          text: "Lanjut",
          type: "success",
          action: () => {
            console.log("Continue action");
            if (onConfirm) onConfirm(); // Jalankan fungsi callback jika ada
          },
        },
      ],
    });
  }

  static info(title, message, duration = null) {
    this.create({
      type: "info",
      title,
      message,
      duration,
      buttons: [
        {
          text: "Mengerti",
          type: "info",
          action: () => console.log("Info action"),
        },
      ],
    });
  }

  static read(title, message, duration = 500) {
    this.create({
      type: "success",
      title,
      message,
      duration,
      buttons: [],
    });
  }

  static show(title, message, duration = 3000) {
    this.create({
      type: "success",
      title,
      message,
      duration,
      buttons: [],
    });
  }
}

// popup end
