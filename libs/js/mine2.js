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

    // Progress bar
    const progressContainer = document.createElement("div");
    progressContainer.className = "popup-progress";

    const progressBar = document.createElement("div");
    progressBar.className = `popup-progress-bar ${config.type}-bg`;
    progressContainer.appendChild(progressBar);

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

    // Close button
    const closeButton = document.createElement("button");
    closeButton.className = "popup-close";
    closeButton.innerHTML = "×";
    closeButton.onclick = () => this.close(backdrop);
    container.appendChild(closeButton);

    // Gabungkan semua elemen
    container.appendChild(icon);
    container.appendChild(title);
    container.appendChild(message);
    if (config.buttons.length > 0) container.appendChild(buttons);
    if (config.duration) container.appendChild(progressContainer);
    backdrop.appendChild(container);
    document.body.appendChild(backdrop);

    // Tampilkan popup
    setTimeout(() => backdrop.classList.add("active"), 10);

    // Auto close
    if (config.duration) {
      // Reset dan jalankan progress bar
      setTimeout(() => {
        progressBar.style.animation = `progressBar ${config.duration}ms linear forwards`;
      }, 10);

      setTimeout(() => this.close(backdrop), config.duration);
    }

    // Close saat klik di luar
    backdrop.addEventListener("click", (e) => {
      if (e.target === backdrop) this.close(backdrop);
    });

    // Close saat tekan ESC
    const escapeHandler = (e) => {
      if (e.key === "Escape") this.close(backdrop);
    };
    document.addEventListener("keydown", escapeHandler);

    // Simpan handler untuk dihapus nanti
    backdrop._escapeHandler = escapeHandler;
  }

  static close(backdrop) {
    backdrop.classList.remove("active");
    // Hapus event listener
    document.removeEventListener("keydown", backdrop._escapeHandler);
    setTimeout(() => {
      if (backdrop.parentNode) {
        backdrop.parentNode.removeChild(backdrop);
      }
    }, 400);
  }

  static getIcon(type) {
    const icons = {
      success: "✓",
      error: "✕",
      warning: "⚠",
      info: "ℹ",
      read: "📖",
    };
    return icons[type] || "";
  }

  static success(title, message, duration) {
    this.create({
      type: "success",
      title,
      message,
      duration: duration || 4000,
      buttons: [
        {
          text: "OK",
          type: "success",
          action: () => {},
        },
      ],
    });
  }

  static error(title, message, duration) {
    this.create({
      type: "error",
      title,
      message,
      duration: duration || 5000,
      buttons: [
        {
          text: "Tutup",
          type: "error",
          action: () => {},
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
          type: "read",
          action: () => {},
        },
        {
          text: "Lanjut",
          type: "warning",
          action: () => {
            if (onConfirm) onConfirm();
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
          action: () => {},
        },
        {
          text: "Lanjut",
          type: "success",
          action: () => {
            if (onConfirm) onConfirm();
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
      duration: duration || 4000,
      buttons: [
        {
          text: "Mengerti",
          type: "info",
          action: () => {},
        },
      ],
    });
  }

  static read(title, message, duration = 3000) {
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
