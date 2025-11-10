import { useMeToast } from "@/core/hooks/ToastStore";
import type { IRepository } from "@/modules/home/domain/repositories/IRepository";

export class GetApprovePaymentPaypal {
  private repository: IRepository;

  constructor(repo: IRepository) {
    this.repository = repo;
  }
  async execute(id: string) {
    const meToast = useMeToast();
    try {
      this.repository.setEndPoint(`/payments/paypal/approve/${id}`);
      const data = await this.repository.send([]);
      meToast.addToast({ message: 'consulta realizada con éxito', type: 'success', duration: 5000 });
      return data;
    } catch (error) {
      console.error('Error fetching home data:', error);
      meToast.addToast({ message: 'error', type: 'error', duration: 4000 });

      throw error;
    }
  }
}
