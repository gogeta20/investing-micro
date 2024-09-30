import { defineStore } from "pinia";

export const useToastStore = defineStore({
  id: "toast",
  state: () => ({
    title: "",
    message: "",
    type: "",
    code: 200,
  }),

  actions: {
    onShowToast({
      title,
      message,
      type,
      code,
    }: {
      title: string;
      message: string;
      type: string;
      code: number;
    }) {
      this.title = title;
      this.message = message;
      this.type = type;
      this.code = code;
    },
  },
});
