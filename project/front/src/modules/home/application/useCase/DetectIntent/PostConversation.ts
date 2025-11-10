import { useMeToast } from "@/core/hooks/ToastStore";
import { iconversationState } from "@/modules/home/domain/models/IConversationState";
import type { IRepository } from "@/modules/home/domain/repositories/IRepository";

export class PostConversation{
  private repository: IRepository;

  constructor(repo: IRepository) {
    this.repository = repo;
  }
  async execute(params: iconversationState) {
    const meToast = useMeToast();
    try {
      this.repository.setEndPoint(`/api/intent/${params.intent}`);
      const data = await this.repository.send(params);
      meToast.addToast({ message: 'consulta realizada con éxito', type: 'success', duration: 5000 });
      return data;
    } catch (error) {
      console.error('Error fetching home data:', error);
      meToast.addToast({ message: 'error', type: 'error', duration: 4000 });

      throw error;
    }
  }
}
